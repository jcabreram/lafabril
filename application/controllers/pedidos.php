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
			if($id_pedido = $this->orders->register($_POST['branch'], $_POST['salesman'], $_POST['client'], $_POST['fecha_pedido'], $_POST['fecha_entrega'], 'P', $usuario_captura)) {
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

	private function _sanitizeFilters($dirtyFilters)
	{
		$filters = array();

		if (isset($dirtyFilters['sucursal']) && trim($dirtyFilters['sucursal']) !== '') {
			$filters['branch'] = $dirtyFilters['sucursal'];
		}

		if (isset($dirtyFilters['cliente']) && trim($dirtyFilters['cliente']) !== '') {
			$filters['client'] = $dirtyFilters['cliente'];
		}

		if (isset($dirtyFilters['estatus']) && trim($dirtyFilters['estatus']) !== '') {
			switch ($dirtyFilters['estatus']) {
				case 'abierto':
					$filters['status'] = 'A';
					break;

				case 'cerrado':
					$filters['status'] = 'C';
					break;

				case 'cancelado':
					$filters['status'] = 'X';
					break;
			}
		}
		
		return $filters;
	}

	public function listar()
	{
		// We need it to populate the filter form
		$this->load->helper('form');

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// To populate the filter form
		$this->load->model('branches');
		$this->load->model('clients');

		// Get orders
		$data['ordersData'] = $this->orders->getAll($filters);
		
		$data['title'] = "Pedidos";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->branches->getAll(array('status' => '1')); // Active branches
		$data['clients'] = $this->clients->getAll(array('status' => '1')); // Active clients
		$data['filters'] = $filters;

		// Display views
		$this->load->view('header', $data);
		$this->load->view('pedidos/listar', $data);
		$this->load->view('pedidos/filterForm', $data);
		$this->load->view('footer', $data);
	}

	public function filtrar()
	{
		if ($_POST) {
			$filters = array();

			$branch = isset($_POST['branch']) ? trim($_POST['branch']) : false;
			$client = isset($_POST['client']) ? trim($_POST['client']) : false;
			$status = isset($_POST['status']) ? trim($_POST['status']) : false;

			if ($branch !== false && $branch !== '') {
				// Is a numeric value? I mean, is it an id?
				$filters['sucursal'] = $branch;
			}

			if ($client !== false && $client !== '') {
				// Is a numeric value? I mean, is it an id?
				$filters['cliente'] = $client;
			}

			if ($status !== false && $status !== '') {
				switch ($status) {
					case 'A':
						$filters['estatus'] = 'abierto';
						break;

					case 'C':
						$filters['estatus'] = 'cerrado';
						break;

					case 'X':
						$filters['estatus'] = 'cancelado';
						break;
				}
			}

			if (count($filters) > 0) {
				redirect('pedidos/listar/' . $this->uri->assoc_to_uri($filters));
			} else {
				redirect('pedidos');
			}
		}

		// WTH is the user doing here?
		redirect();
	}

	public function exportar()
	{
		$this->load->helper(array('dompdf', 'file'));

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// To populate the filters data
		$this->load->model('branches');
		$this->load->model('clients');
		
		$orders = $this->orders->getAll($filters);

		if (!isset($filters['branch'])) {
			$branch = 'Todos';
		} else {
			$branch = $this->branches->getBranch($filters['branch']);
			$branch = $branch['nombre'];
		}

		if (!isset($filters['client'])) {
			$client = 'Todos';
		} else {
			$client = $this->clients->getBranch($filters['client']);
			$client = $client['nombre'];
		}

		if (!isset($filters['status'])) {
			$status = 'Todos';
		} else {
			switch ($filters['status']) {
				case 'A':
					$status = 'Abierto';
					break;

				case 'C':
					$status = 'Cerrado';
					break;

				case 'X':
					$status = 'Cancelado';
					break;
			}
		}

		$data['title'] = "Reporte de Pedidos";
		$data['orders'] = $orders;
		$data['branch'] = $branch;
		$data['client'] = $client;
		$data['status'] = $status;

		$html = $this->load->view('reportes/header', $data, true);
		$html .= $this->load->view('reportes/pedidos', $data, true);
		$html .= $this->load->view('reportes/footer', $data, true);
		createPDF($html, 'reporte');
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

		$data['title'] = "Registrar detalles del pedido";
		$data['user'] = $this->session->userdata('user');
		$data['order'] = $this->orders->getOrder($id_pedido);
		//$data['sucursal'] = $this->branches->getBranch($data['order']['id_sucursal']);
		//$data['vendedor'] = $this->salesmen->getSalesman($data['order']['id_vendedor']);
		//$data['cliente'] = $this->clients->getClient($data['order']['id_cliente']);
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
	
	public function detalles($id_pedido)
	{
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('salesmen');
		$this->load->model('clients');
		$this->load->model('branches');
		$this->load->model('orders');
		$this->load->model('products');


		$data['title'] = "Detalles del pedido";
		$data['user'] = $this->session->userdata('user');
		$data['order'] = $this->orders->getOrder($id_pedido);
		//$data['sucursal'] = $this->branches->getBranch($data['order']['id_sucursal']);
		//$data['vendedor'] = $this->salesmen->getSalesman($data['order']['id_vendedor']);
		//$data['cliente'] = $this->clients->getClient($data['order']['id_cliente']);
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
		$this->load->view('pedidos/detalles', $data);
		$this->load->view('footer', $data);
	}

	
	public function eliminar($id_pedido, $id)
	{	
		$this->orders->eliminar($id);
		redirect("pedidos/registrar_detalles/$id_pedido");
	}
	
	public function cancelar($id_pedido)
	{	
		if($this->orders->cancelar($id_pedido)) {
				$this->session->set_flashdata('message', 'El pedido ha sido cancelado.');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar cancelar el pedido, intenta de nuevo.');
			}
		redirect("pedidos");
		
	}

	public function facturar()
	{
		$orderId = $this->uri->segment(3);

		if ($orderId === false) {
			// We don't have an order id
			redirect();
		}
		
		/*** GET ORDER AND IT'S PRODUCTS ***/
		$order = $this->orders->getOrder($orderId);

		if (count($order) === 0) {
			// The order doesn't exist
			redirect();
		}

		$order['products'] = $this->orders->getOrderProducts($orderId);
		/*** GET ORDER AND IT'S PRODUCTS ***/


		// If the order is not open...
		if ($order['estatus'] !== 'A') {
			redirect();
		}

		
		/*** VALIDATION ***/
		if ($_POST) {
			$errors = array(
				'date' => array(),
				'products' => array()
			);

			
			/*** VALIDATE INVOICE DATE ***/
			if (isset($_POST['invoiceDate']) && count(explode('/', $_POST['invoiceDate'])) === 3) {
				// Change date to a more managable format
				$invoiceDate = explode('/', $_POST['invoiceDate']);
				$invoiceDate = $invoiceDate['1'] . '/' . $invoiceDate['0'] . '/' . $invoiceDate['2'];

				$timestamp = strtotime($invoiceDate);
				$day = date('d', $timestamp);
				$month = date('m', $timestamp);
				$year = date('Y', $timestamp);
				
				if (!checkdate($month, $day, $year)) {
					$errors['date'][] = 'Fecha inválida.';
				}

				if ($timestamp < strtotime($order['fecha_pedido'])) {
					$errors['date'][] = 'La fecha de la factura debe ser posterior a la del pedido.';
				}
			} else {
				// Is there no date?
				exit('Is there no date?');
				redirect();
			}
			/*** VALIDATE INVOICE DATE ***/


			/*** VALIDATE PRODUCTS ***/
			if (isset($_POST['products']) && is_array($_POST['products'])) {
				// Products to be saved into our database
				$products = array(); 

				foreach ($_POST['products'] as $productId => $productAmount) {
					if ($productAmount === '' || $productAmount === '0') {
						continue;
					}

					$filterOptions = array(
						'options' => array(
							'min_range' => 1
						)
					);

					// Is $productId a natural number starting on 1?
					if (filter_var($productId, FILTER_VALIDATE_INT, $filterOptions) === false) {
						// $productId is not a number?
						exit('$productId is not a number?');
						redirect();
					}

					// Is $productAmount not a negative number?
					if (!is_numeric($productAmount) || floatval($productAmount) < 0) {
						$errors['products'][$productId] = 'Escribe un número positivo.';
						continue;
					}


					/*** VERIFY IF THE PRODUCT IS IN THE ORDER ***/
					$inTheOrder = false;

					foreach ($order['products'] as $key => $product) {
						if ($productId === intval($product['id_producto'])) {
							$inTheOrder = $key;
						}
					}
					/*** VERIFY IF THE PRODUCT IS IN THE ORDER ***/


					if ($inTheOrder !== false) {
						// Amount ordered - Amount delivered
						$maximumAmount = $order['products'][$inTheOrder]['cantidad'] - $order['products'][$inTheOrder]['cantidad_surtida'];

						if ($productAmount > $maximumAmount) {
							$errors['products'][$productId] = 'Máximo ' . $maximumAmount . ' ' . $order['products'][$inTheOrder]['udm'] . '.';
							continue;
						} else {
							$products[$productId] = array('amount' => $productAmount, 'price' => $order['products'][$inTheOrder]['precio']);
						}
					} else {
						// You sent me a product not in the order?
						exit('You sent me a product not in the order?');
						redirect();
					}
				}
			} else {
				// The form was sent and there aren't products?
				// I don't have an error for that...
				exit('The form was sent and there are no products?');
				redirect();
			}
			/*** VALIDATE PRODUCTS ***/
		}
		/*** VALIDATION ***/


		// If validation was successful & there are products to invoice for & if the order status is open
		if ($_POST && count($errors['date']) === 0 && count($errors['products']) === 0 && count($products) > 0 && $order['estatus'] === 'A') {
			// We need his data for damage control
			$user = $this->session->userdata('user');

			if ($this->orders->invoice($order, $products, date('Y-m-d', $timestamp), $user['id'])) {
				$this->session->set_flashdata('message', 'El pedido ha sido facturado.');
				redirect('pedidos');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar facturar el pedido, intenta de nuevo.');
			}
		}

		$data['title'] = 'Facturar Pedido';
		$data['user'] = $this->session->userdata('user');
		$data['order'] = $order;

		// To show errors if there is any
		if (isset($errors) && count($errors['products']) > 0) {
			$data['errors']['products'] = $errors['products'];
		}

		// To show errors if there is any
		if (isset($errors) && count($errors['date']) > 0) {
			$data['errors']['date'] = $errors['date'][0];
		}

		$this->load->view('header', $data);
		$this->load->view('pedidos/facturar', $data);
		$this->load->view('footer', $data);
	}

	public function crear_nota_venta()
	{
		$orderId = $this->uri->segment(3);

		// If the order id was not supplied...
		if ($orderId === false) {
			$this->session->set_flashdata('error', 'Dirección inválida.');
			redirect('pedidos');
		}

		$order = $this->orders->getOrder($orderId);

		// If the order doesn't exist...
		if (count($order) === 0) {
			$this->session->set_flashdata('error', 'Pedido inexistente.');
			redirect('pedidos');
		}
		
		// If the order is not open...
		if ($order['estatus'] !== 'A') {
			$this->session->set_flashdata('error', 'Pedido no abierto.');
			redirect('pedidos');
		}

		$order['products'] = $this->orders->getOrderProducts($orderId);
		
		foreach ($order['products'] as $product) {
			if (floatval($product['cantidad_surtida']) !== 0.0) {
				$this->session->set_flashdata('error', 'El pedido ya ha empezado a ser facturado');
				redirect('pedidos');
			}
		}

		$subtotal = 0.0;
		$taxes = 0.0;
		$total = 0.0;
		
		foreach ($order['products'] as $product) {
			$subtotal += $product['cantidad'] * $product['precio'];
		}

		$taxes = $subtotal * $order['sucursal_iva'];
		$total = round($subtotal + $taxes, 2);
		
		$user = $this->session->userdata('user');
		$errors = array();

		if ($_POST && count($errors = $this->_validateBillPayment($_POST, $order['fecha_pedido'], $total)) === 0) {
			$this->load->model('bills');
			
			$cash = !isset($_POST['cash']) || $_POST['cash'] === '' ? 0 : floatval($_POST['cash']);
			$cards = !isset($_POST['cards']) || $_POST['cards'] === '' || !is_array($_POST['cards']) ? array() : $_POST['cards'];
			$checks = !isset($_POST['checks']) || $_POST['checks'] === '' || !is_array($_POST['checks']) ? array() : $_POST['checks'];
			
			if ($cash > $total) {
				$cash = $total;
			}
			
			$payments = array(
				'cash' => $cash,
				'cards' => $cards,
				'checks' => $checks
			);

			if (($billId = $this->bills->register($order, convertToComputerDate($_POST['billDate']), $payments, $user['id'])) !== false) {
				$message = '<a href="'.site_url('notas_venta/detalles/'.$billId).'" title="Ver nota de venta">Nota de venta creada</a>.';
				$this->session->set_flashdata('message', $message);
				redirect('pedidos');
			} else {
				$message = 'Tuvimos un problema al intentar crear la nota, intenta de nuevo en 10 minutos.';
				$this->session->set_flashdata('error', $message);
			}
		}

		$data['title'] = 'Crear Nota de Venta';
		$data['user'] = $user;
		$data['order'] = $order;
		$data['subtotal'] = $subtotal;
		$data['taxes'] = $taxes;
		$data['total'] = $total;
		$data['errors'] = $errors;

		$this->load->view('header', $data);
		$this->load->view('pedidos/crear_nota_venta', $data);
		$this->load->view('pedidos/cardForm', $data);
		$this->load->view('pedidos/checkForm', $data);
		$this->load->view('footer', $data);	
	}
	
	private function _validateBillPayment($data, $orderDate, $total)
	{
		$errors = array();

		$billDate = !isset($data['billDate']) ? '' : $data['billDate'];
		$cash = !isset($data['cash']) ? '' : $data['cash'];
		$cards = !isset($data['cards']) ? '' : $data['cards'];
		$checks = !isset($data['checks']) ? '' : $data['checks'];
		
		$cash = $cash === '' ? '0' : $cash;
		$cards = $cards === '' || !is_array($cards) ? array() : $cards;
		$checks = $checks === '' || !is_array($checks) ? array() : $checks;
		
		$billDateParts = explode('/', $billDate);
		
		if ($billDate === '') {
			$errors['billDate'] = 'La fecha de la nota es obligatoria';
		} elseif (count($billDateParts) !== 3 || !checkdate($billDateParts[1], $billDateParts[0], $billDateParts[2])) {
			$errors['billDate'] = 'Fecha inválida';
		} elseif (strtotime(convertToComputerDate($billDate)) < strtotime($orderDate)) {
			$errors['billDate'] = 'La fecha de la nota debe ser posterior a la del pedido';
		}
		
		if (!is_numeric($cash)) {
			$cash = 0;
			$errors['cash'] = 'Escribe una cantidad de efectivo válida';	
		} elseif (($cash = floatval($cash)) < 0) {
			$cash = 0;
			$errors['cash'] = 'Escribe una cantidad de efectivo positiva';
		}
		
		$maxCardNumLength = 19;
		$minCardNumLength = 14;
		$paidWithCards = 0;
		
		foreach ($cards as $cardInformation => $paymentAmount) {
			$cardInformation = explode('-', $cardInformation);
			
			if (count($cardInformation) !== 2) {
				$errors['cards'] = 'Error al procesar las tarjetas';
				break;
			}
			
			$cardBank = $cardInformation[0];
			$cardNumber = $cardInformation[1];
			$cardNumLength = strlen($cardNumber);
				
			if (!ctype_digit($cardNumber) || $cardNumLength < $minCardNumLength || $cardNumLength > $maxCardNumLength) {
				$errors['cards'] = 'Error procesando la información de las tarjetas';
				break;
			}
				
			if (!is_numeric($paymentAmount) || floatval($paymentAmount) < 0) {
				$errors['cards'] = 'Error procesando la información de las tarjetas';
			}
			
			$paidWithCards += floatval($paymentAmount);
		}
		
		$maxCheckNumLength = 45;
		$minCheckNumLength = 4;
		$paidWithChecks = 0;
		
		foreach ($checks as $checkInformation => $paymentAmount) {
			$checkInformation = explode('-', $checkInformation);
			
			if (count($checkInformation) !== 2) {
				$errors['checks'] = 'Error al procesar los cheques';
				break;
			}
			
			$checkBank = $checkInformation[0];
			$checkNumber = $checkInformation[1];
			$checkNumLength = strlen($checkNumber);
				
			if (!ctype_digit($checkNumber) || $checkNumLength < $minCheckNumLength || $checkNumLength > $maxCheckNumLength) {
				$errors['checks'] = 'Error procesando la información de los cheques';
				break;
			}
				
			if (!is_numeric($paymentAmount) || floatval($paymentAmount) < 0) {
				$errors['checks'] = 'Error procesando la información de los cheques';
			}
			
			$paidWithChecks += floatval($paymentAmount);
		}
		
		$totalPaid = $cash + $paidWithCards + $paidWithChecks;

		if ($totalPaid < $total) {
			$errors['overall'] = 'Necesitas pagar el total del pedido';
		} elseif ($cash < $total && ($paidWithCards + $paidWithChecks) > ($total - $cash)) {
			$errors['overall'] = 'No puedes pagar de más del total con las tarjetas y/o cheques. x='.$totalPaid.' vs y='.$total;
		}
		
		return $errors;
	}

	public function imprimir($id)
	{
		$order = $this->orders->getOrder($id);

		if (count($order) === 0) {
			// We kill the script because usually the PDF is opened in a different tab.
			exit('Orden no encontrada.');
		}

		// Necessary to create a PDF
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('branches'); // This is mandatory to create the PDF header
		$this->load->model('clients'); // This model is necessary because the format has the client address

		$order['products'] = $this->orders->getOrderProducts($id);
		$branch = $this->branches->getBranch($order['id_sucursal']);
		$clientAddress = $this->clients->getClientAddress($order['id_cliente']);

		$subtotal = 0;

		foreach ($order['products'] as $product) {
			$subtotal += $product['cantidad'] * $product['precio'];
		}

		$iva = $subtotal * $order['sucursal_iva'];
		$total = $subtotal + $iva;

		$data['title'] = 'Pedido';
		$data['branch'] = $branch;
		$data['folio'] = $order['prefijo'] . str_pad($order['folio'], 9, '0', STR_PAD_LEFT);
		$data['order'] = $order;
		$data['clientAddress'] = $clientAddress;
		$data['subtotal'] = $subtotal;
		$data['iva'] = $iva;
		$data['total'] = $total;

		$html = $this->load->view('formatos/header', $data, true);
		$html .= $this->load->view('formatos/pedido', $data, true);
		$html .= $this->load->view('formatos/footer', $data, true);

		createPDF($html, 'formato');
	}
}