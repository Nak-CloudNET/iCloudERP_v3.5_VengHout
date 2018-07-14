<link href="<?= $assets ?>styles/screen.css" rel="stylesheet" />
<script>
	$(document).ready(function(){
		$('#main-menu-act').trigger('click');
		$('#screen').on('click', function(){
			$('#main-menu-act').trigger('click');
		});
	});
</script>
<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12 wrapper">
			<div class="col-md-8 col-sm-8 col-xs-8 wrap-left">
				<div class="col-md-12 col-sm-12 col-xs-12 vender">
					<div class="col-md-1 col-sm-1 col-xs-1">
						<div id="vend">Vender</div>
					</div>
					<div class="col-md-11 col-sm-11 col-xs-11 ven-content">
						<div class="col-md-12 col-sm-12 col-xs-12 zero">
							<div class="col-md-3 col-sm-3 col-xs-3">
								<a href="" class="vender-box">
									<div class="">
										<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Purchase Order</div>
								</a>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<a href="" class="vender-box">
									<div class="">
										<i class="fa fa-qrcode fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Receive Inventory</div>
								</a>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<a href="" class="vender-box">
									<div class="">
										<i class="fa fa-folder-open-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="" style="background:#f3f3f3;">Enter Bill Against Inventory</div>
								</a>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<a href="" class="vender-box">
									<div class="">
										<i class="fa fa-fax fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Pay Sales Tax</div>
								</a>
							</div>
						</div>
						
						<div class="col-md-12 col-sm-12 col-xs-12 vline-top">
							<div class="col-md-4 col-sm-4 col-xs-4">
								<div class="col-md-6 col-sm-7 col-xs-6"></div>
								<div class="col-md-6 col-sm-5 col-xs-6 border"></div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
								<div class="col-md-3 col-sm-1 col-xs-6"></div>
								<div class="col-md-5 col-sm-5 col-xs-6 border"><i aria-hidden="true" class="fa fa-long-arrow-right col-md-12 col-sm-6 col-xs-6"></i></div>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3"></div>
						</div>
						
						<div class="col-md-12 col-sm-12 col-xs-12 vline-down">
							<div class="col-md-4 col-sm-4 col-xs-4">
								<div class="col-md-6 col-sm-6 col-xs-6"></div>
								<div class="col-md-6 col-sm-6 col-xs-6 "></div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
								<div class="col-md-9 col-sm-9 col-xs-9"></div>
								<div class="col-md-1 col-sm-1 col-xs-11 bdl"></div>
							</div>
						</div>
						
						<div class="col-md-12 col-sm-12 col-xs-12 one">
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="vender-box">
									<div class="">
										<i class="fa fa-folder-open-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Enter Bills</div>
								</a>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="vender-box">
									<div class="">
										<i class="fa fa-envelope-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Pay Bills</div>
								</a>
							</div>
						</div>
						
						<div class="col-md-12 col-sm-12 col-xs-12 vline-left">
							<div class="col-md-4 col-sm-3 col-xs-3"></div>
							<div class="col-md-4 col-sm-6 col-xs-6 vlb">
								<i aria-hidden="true" class="fa fa-long-arrow-right col-md-12 col-sm-6 col-xs-6"></i>
							</div>
						</div>
						
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 customer">
					<div class="col-md-1 col-sm-1 col-xs-1">
						<div id="cust">Customer</div>
					</div>
					<div class="col-md-11 col-sm-11 col-xs-11 ven-content">
						<div class="col-md-12 col-sm-12 col-xs-12 zero">
							<div class="col-md-6 col-sm-6 col-xs-6 ">
								<div class="col-md-6 col-sm-6 col-xs-6"></div>
								<div class="col-md-6 col-sm-6 col-xs-6 box-center">
									<a href="" class="customer-box">
										<div class="" >
											<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
										</div>
										<div class="">Online Invoice payments</div>
									</a>
								</div>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 box-center">
								<a href="" class="customer-box">
									<div class="">
										<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Accept Credit Card</div>
								</a>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 box-center">
								<a href="" class="customer-box">
									<div class="">
										<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Create Sale Receipts</div>
								</a>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 four">
							<div class="col-md-2 col-sm-3 col-xs-3 box-center">
								<a href="" class="customer-box">
									<div class="">
										<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Estimates</div>
								</a>
							</div>
							<div class="col-md-2 col-sm-3 col-xs-3 box-center">
								<a href="" class="customer-box">
									<div class="">
										<i aria-hidden="true" class="fa fa-file-text-o fa-size"></i>
									</div>
									<div class="">Create Invoice</div>
								</a>
							</div>
							<div class="col-md-8 col-sm-3 col-xs-4 box-center">
								<div class="col-md-5 col-sm-3 col-xs-2"></div>
								<div class="col-md-5 col-sm-3 col-xs-2">
									<a href="" class="customer-box">
										<div class="">
											<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
										</div>
										<div class="">Receipts Payment</div>
									</a>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 one">
							<div class="col-md-9 col-sm-9 col-xs-9 box-center">
								<div class="col-md-5 col-sm-4 col-xs-4"></div>
								<div class="col-md-2 col-sm-1 col-xs-1">
									<a href="" class="customer-box">
										<div class="">
											<i aria-hidden="true" class="fa fa-file-text-o fa-size" style="background:#F3F3F3;"></i>
										</div>
										<div>Statement Change</div>
									</a>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1">
									<a href="" class="customer-box">
										<div class="">
											<i aria-hidden="true" class="fa fa-file-text-o fa-size" ></i>
										</div>
										<div class="">Finance Change</div>
									</a>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1">
									<a href="" class="customer-box">
										<div class="">
											<i aria-hidden="true" class="fa fa-file-text-o fa-size" ></i>
										</div>
										<div class="">Statements</div>
									</a>
								</div>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 box-center">
								<a href="" class="customer-box">
									<div class="">
										<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Refunds and Credits</div>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 employee">
					<div class="col-md-1 col-sm-1 col-xs-1">
						<div id="empl">Employee</div>
					</div>
					<div class="col-md-11 col-sm-11 col-xs-11 ven-content">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="col-md-2 col-sm-3 col-xs-3 box-center">
								<a href="" >
									<div class="">
										<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Turn on Payroll</div>
								</a>
							</div>
							<div class="col-md-2 col-sm-3 col-xs-3 box-center">
								<a href="">
									<div class="">
										<i class="fa fa-file-text-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Enter Time</div>
								</a>
							</div>
							<div class="col-md-8 col-sm-6 col-xs-6"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 wrap-right">
				<div class="col-md-12 col-sm-12 col-xs-12 company">
					<div class="col-md-10 col-sm-10 col-xs-10 ven-content">
						<div class="col-md-12 col-sm-12 col-xs-12 zero">
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="company-box">
									<div class="">
										<i class="fa fa-folder-open-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Chart of Accounts</div>
								</a>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="company-box">
									<div class="">
										<i class="fa fa-envelope-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Transaction Center</div>
								</a>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 one">
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="company-box">
									<div class="">
										<i class="fa fa-folder-open-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Items and Services</div>
								</a>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="company-box">
									<div class="">
										<i class="fa fa-envelope-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Report Center</div>
								</a>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 three">
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="company-box">
									<div class="">
										<i class="fa fa-folder-open-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Adjust Quantity on Hand</div>
								</a>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="company-box">
									<div class="">
										<i class="fa fa-envelope-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Reminders</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div id="comp">Company</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 banking">
					<div class="col-md-10 col-sm-10 col-xs-10 ven-content">
						<div class="col-md-12 col-sm-12 col-xs-12 four">
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="ban-box">
									<div class="">
										<i class="fa fa-folder-open-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Record Deposits</div>
								</a>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="ban-box">
									<div class="">
										<i class="fa fa-envelope-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Reconcile</div>
								</a>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 five">
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="ban-box">
									<div class="">
										<i class="fa fa-folder-open-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Write Checks</div>
								</a>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="ban-box">
									<div class="">
										<i class="fa fa-envelope-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Checks Register</div>
								</a>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 six">
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="ban-box">
									<div class="">
										<i class="fa fa-folder-open-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Print Checks</div>
								</a>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 box-center">
								<a href="" class="ban-box">
									<div class="">
										<i class="fa fa-envelope-o fa-size" aria-hidden="true"></i>
									</div>
									<div class="">Enter Credit Card Charges</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div id="bank">Banking</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>