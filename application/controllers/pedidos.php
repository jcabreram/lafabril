<?php

class Pedidos extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Is user not logged in?
		if (!$this->session->userdata('user')) {
			redirect('ingresar');
		}
		// User doesn't want to be remembered?
		elseif (!$this->session->userdata('remember') && (($this->session->userdata('lastActivity') + $this->config->item('maximumIdleTime')) < time())) {
			redirect('salir');
		}

		// We need it to know his idle time
		$this->session->set_userdata('lastActivity', time());

		$this->load->model('orders');
	}

	public function index()
	{
		$this->listar();
	}

	public function registrar()
	{
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('salesmen');
		$this->load->model('clients');

		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'branch', 
				'label' => 'sucursal', 
				'rules' => 'callback_not_default'
			),
			array(
				'field' => 'salesman', 
				'label' => 'vendedor', 
				'rules' => 'callback_not_default'
			),
			array(
				'field' => 'client', 
				'label' => 'cliente', 
				'rules' => 'callback_not_default'
			),
			array(
				'field' => 'fecha_pedido', 
				'label' => 'fecha del pedido', 
				'rules' => 'required|exact_length[10]|alpha_dash'
			),
			array(
				'field' => 'fecha_entrega', 
				'label' => 'fecha de entrega', 
				'rules' => 'required|exact_length[10]|alpha_dash|callback_end_date_check'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			$usuario = $this->session->userdata('user');
			$usuario_captura = $usuario['id'];
			if($id_pedido = $this->orders->register($_POST['branch'], $_POST['salesman'], $_POST['client'], $_POST['fecha_pedido'], $_POST['fecha_entrega'], 'A', $usuario_captura)) {
				redirect("pedidos/registrar_detalles/$id_pedido");
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar el pedido, intenta de nuevo.');
				redirect("pedidos/registrar");
			}
		}

		$data['title'] = "Registrar Pedido";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->userBranches->getActiveUserBranches($data['user']['id']);
		$data['salesmen'] = $this->salesmen->getAll();
		$data['clients'] = $this->clients->getActiveClients();
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pedidos/registrar', $data);
		$this->load->view('footer', $data);
	}
	
	public function not_default($str) {
		if ($str == 'escoge') {
			$this->form_validation->set_message('not_default', 'Escoge una opción');
			return FALSE;
		} else {
	    	return TRUE;
	    }
	}
	
	public function end_date_check() 
	{
	    if(strtotime($this->input->post('fecha_pedido')) > strtotime($this->input->post('fecha_entrega'))) 
	    { 
	        $this->form_validation->set_message('end_date_check', 'La fecha de entrega debe de ser posterior a la de pedido.');
	        return FALSE;
	    }
	    else 
	    {
	        return TRUE;
	    }
	} 

	public function listar()
	{
		
	}
	
	public function registrar_detalles($id_pedido)
	{
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('salesmen');
		$this->load->model('clients');
		$this->load->model('branches');
		$this->load->model('orders');
		$this->load->model('products');

		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'cantidad', 
				'label' => 'cantidad', 
				'rules' => 'required|greater_than[0]'
			),
			array(
				'field' => 'precio', 
				'label' => 'precio unitario', 
				'rules' => 'required|greater_than[0]'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			$usuario = $this->session->userdata('user');
			if($this->orders->addLine($id_pedido, $_POST['id_producto'], $_POST['cantidad'], $_POST['precio'])) {
				$this->session->set_flashdata('message', 'El pedido ha sido registrado.');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar el pedido, intenta de nuevo.');
			}
		}

		$data['title'] = 'Registrar detalles del pedido';
		$data['user'] = $this->session->userdata('user');
		$data['order'] = $this->orders->getOrder($id_pedido);
		$data['products'] = $this->products->getProducts();
		$data['order_details'] = $this->orders->getOrderDetail($id_pedido);
		$data['order_id'] = $id_pedido;
		
		// Declare the $subtotal as float so it gets it in the foreach
		settype($subtotal, "float");
		
		// For every detail of the order, gather the sum of the product of the prices and quantities
		foreach ($data['order_details'] as $line) {
			$subtotal+=$line['cantidad']*$line['precio'];
		}
		
		$data['subtotal'] = $subtotal;
		
		// The total is equal to the subtotal plus its tax
		$data['total'] = $subtotal + $subtotal * $data['order']['sucursal_iva']; 
		
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pedidos/registrar_detalles', $data);
		$this->load->view('footer', $data);
	}
	
	public function eliminar($id_pedido, $id)
	{	
		$this->orders->eliminar($id);
		redirect("pedidos/registrar_detalles/$id_pedido");
	}

	public function imprimir($id)
	{
		// Necessary to create a PDF
		$this->load->helper(array('dompdf', 'file'));

		$this->load->model('branches');
		$this->load->model('clients');

		$data['title'] = 'Pedido';
		$data['order'] = $this->orders->getOrder($id);
		$data['branch'] = $this->branches->getBranch($data['order']['id_sucursal']);
		$client = $this->clients->getClient($data['order']['id_cliente']);
		
		$data['clientAddress'] =  $client['calle'] . ' #' . $client['numero_exterior'];
		
		if ($client['numero_interior'] !== null) {
			$data['clientAddress'] .= ' interior ' . $client['numero_interior'];
		}

		$data['clientAddress'] .= ' ' . $client['colonia'] . '. ' . $client['ciudad'] 
		. ', ' . $client['municipio'] . ', ' . $client['estado'] . ', ' . $client['pais']
		. '. C.P. ' . $client['codigo_postal'];

		$data['subtotal'] = 0;

		foreach ($data['order']['products'] as $product) {
			$data['subtotal'] += $product['cantidad'] * $product['precio'];
		}

		$data['iva'] = $data['subtotal'] * $data['order']['sucursal_iva'];
		$data['total'] = $data['subtotal'] + $data['iva'];

		$html = $this->load->view('formatos/header', $data, true);
		$html .= $this->load->view('formatos/pedido', $data, true);
		$html .= $this->load->view('formatos/footer', $data, true);

		createPDF($html, 'formato');
	}
}