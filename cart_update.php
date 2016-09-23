
	// ------------------------------------------------
    // Обработка уведомления об оплате от Fondy 
    // ------------------------------------------------
	if (isset($_GET['paymentcallback']) and $_GET['paymentcallback'] == 'fondy')
	{
		if (empty($_POST)){
			$input = json_decode(file_get_contents("php://input"));
			$_POST = array();
			foreach($input as $key=>$val)
			{
				$_POST[$key] =  $val ;
			}
		}	
		if (isset($_POST['order_status']) and $_POST['order_status'] == 'approved')
		{
            $order_id = explode('#', $_POST['order_id']); // Номер заказа, переданный системой после оплаты
            $oShop_Order = Core_Entity::factory('Shop_Order')->find($order_id[0]);
            if (!is_null($oShop_Order->id))
            {
                // Вызов обработчика платежной системы
                Shop_Payment_System_Handler::factory($oShop_Order->Shop_Payment_System)
				->shopOrder($oShop_Order)
				->paymentProcessing();
			}
            exit();       
		}
	}
    // ------------------------------------------------
    // конец обработчика Fondy
	// ------------------------------------------------