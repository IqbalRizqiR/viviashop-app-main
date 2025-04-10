<?php $__env->startSection('content'); ?>

<section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h2 class="text-dark font-weight-medium">Order ID #<?php echo e($order->code); ?></h2>
                <?php if($order->attachments != null): ?>
							<a href="<?php echo e(asset('/storage/' . $order->attachments)); ?>" class="btn btn-primary">See attachments</a>
						<?php endif; ?>
                <div class="btn-group float-right">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row pt-2 mb-3">
                    <div class="col-lg-4">
                        <p class="text-dark" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Billing Address</p>
                        <address>
                            <?php echo e($order->customer_full_name); ?>

                             <?php echo e($order->customer_address1); ?>

                             <?php echo e($order->customer_address2); ?>

                            <br> Email: <?php echo e($order->customer_email); ?>

                            <br> Phone: <?php echo e($order->customer_phone); ?>

                            <br> Postcode: <?php echo e($order->customer_postcode); ?>

                        </address>
                    </div>
                    <div class="col-lg-4">
                        <p class="text-dark" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Shipment Address</p>
                        <?php if($order->shipment != null): ?>
                            <address>
                                <?php echo e($order->shipment->first_name); ?> <?php echo e($order->shipment->last_name); ?>

                                    <?php echo e($order->shipment->address1); ?>

                                    <?php echo e($order->shipment->address2); ?>

                                <br> Email: <?php echo e($order->shipment->email); ?>

                                <br> Phone: <?php echo e($order->shipment->phone); ?>

                                <br> Postcode: <?php echo e($order->shipment->postcode); ?>

                            </address>
                        <?php else: ?>
                            <address>
                            <br> Ambil di Toko
                        </address>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-4">
                        <p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Details</p>
                        <address>
                            ID: <span class="text-dark">#<?php echo e($order->code); ?></span>
                            <br> <?php echo e($order->order_date); ?>

                            <br> Status: <?php echo e($order->status); ?> <?php echo e($order->isCancelled() ? '('. $order->cancelled_at .')' : null); ?>

                            <?php if($order->isCancelled()): ?>
                                <br> Cancellation Note : <?php echo e($order->cancellation_note); ?>

                            <?php endif; ?>
                            <br> Payment Status: <?php echo e($order->payment_status); ?>

                            <br> Payment Method: <?php echo e($order->payment_method); ?>

                            <br> Shipped by: <?php echo e($order->shipping_service_name); ?>

                        </address>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Total</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                function showAttributes($jsonAttributes)
                                {
                                    $jsonAttr = (string) $jsonAttributes;
                                    $attributes = json_decode($jsonAttr, true);
                                    $showAttributes = '';
                                    if ($attributes) {
                                        $showAttributes .= '<ul class="item-attributes list-unstyled">';
                                        foreach ($attributes as $key => $attribute) {
                                            if(count($attribute) != 0){
                                                foreach($attribute as $value => $attr){
                                                    $showAttributes .= '<li>'.$value . ': <span>' . $attr . '</span><li>';
                                                }
                                            }else {
                                                $showAttributes .= '<li><span> - </span></li>';
                                            }
                                        }
                                        $showAttributes .= '</ul>';
                                    }
                                    return $showAttributes;
                                }
                            ?>
                            <?php $__empty_1 = true; $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($item->sku); ?></td>
                                    <td><?php echo e($item->name); ?></td>
                                    <td><?php echo showAttributes($item->attributes); ?></td>
                                    <td><?php echo e($item->qty); ?></td>
                                    <td>Rp<?php echo e(number_format($item->base_price,0,",",".")); ?></td>
                                    <td>Rp<?php echo e(number_format($item->sub_total,0,",",".")); ?></td>
                                    <td><?php echo e($order->note); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6">Order item not found!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="row ">
                        <?php if($order->payment_method == 'manual' || $order->payment_method == 'qris'): ?>
                            <div class="col-lg-6 justify-content-start col-xl-4 col-xl-3 ml-sm-auto pb-4">
                                <h4>Payment Slip :</h4>
                                <br>
                                <img  src="<?php echo e(asset('/storage/' . $order->payment_slip )); ?>" width="600" alt="">
                            </div>
                        <?php endif; ?>
                        <div class="col-lg-5 justify-content-end col-xl-4 col-xl-3 ml-sm-auto pb-4">
                            <ul class="list-unstyled mt-4">
                                <li class="mid pb-3 text-dark">Subtotal
                                    <span class="d-inline-block float-right text-default">Rp<?php echo e(number_format($order->base_total_price,0,",",".")); ?></span>
                                </li>
                                <li class="mid pb-3 text-dark">Tax(10%)
                                    <span class="d-inline-block float-right text-default">Rp<?php echo e(number_format($order->tax_amount,0,",",".")); ?></span>
                                </li>
                                <li class="mid pb-3 text-dark">Shipping Cost
                                    <span class="d-inline-block float-right text-default">Rp<?php echo e(number_format($order->shipping_cost,0,",",".")); ?></span>
                                </li>
                                <li class="pb-3 text-dark">Unique Code
                                    <span class="d-inline-block float-right">Rp<?php echo e(number_format(($order->grand_total - ($order->base_total_price + $order->shipping_cost)),0,",",".")); ?></span>
                                </li>
                                <li class="pb-3 text-dark">Total
                                    <span class="d-inline-block float-right">Rp<?php echo e(number_format($order->grand_total,0,",",".")); ?></span>
                                </li>
                            </ul>
                            <?php if(!$order->trashed()): ?>
                                    <?php if($order->isPaid() && $order->isConfirmed() && $order->payment_method != 'cod' && $order->payment_method != 'toko'): ?>
                                        <a href="<?php echo e(url('admin/shipments/'. $order->shipment->id .'/edit')); ?>" class="btn btn-block mt-2 btn-lg btn-primary btn-pill"> Procced to Shipment</a>
                                    <?php elseif($order->isPaid() && $order->isConfirmed() && $order->payment_method == 'cod'): ?>
                                        <a href="#" class="btn btn-block mt-2 btn-lg btn-success btn-pill" onclick="event.preventDefault();
                                        document.getElementById('complete-form-<?php echo e($order->id); ?>').submit();"> Mark as Completed</a>
                                        <form class="d-none" method="POST" action="<?php echo e(route('admin.orders.complete', $order)); ?>" id="complete-form-<?php echo e($order->id); ?>">
                                            <?php echo csrf_field(); ?>
                                        </form>
                                    <?php endif; ?>

                                    <?php if(in_array($order->status, [\App\Models\Order::CREATED, \App\Models\Order::CONFIRMED]) && $order->payment_method == 'automatic'): ?>
                                        <a href="<?php echo e(url('admin/orders/'. $order->id .'/cancel')); ?>" class="btn btn-block mt-2 btn-lg btn-warning btn-pill"> Cancel</a>

                                    <?php elseif(in_array($order->status, [\App\Models\Order::CREATED, \App\Models\Order::CONFIRMED]) && $order->payment_method == 'manual' || $order->payment_method == 'cod' || $order->payment_method == 'qris' && $order->isPaid()): ?>
                                    <a href="<?php echo e(url('admin/orders/'. $order->id .'/cancel')); ?>" class="btn btn-block mt-2 btn-lg btn-warning btn-pill"> Cancel</a>
                                    <?php elseif(in_array($order->status, [\App\Models\Order::CREATED, \App\Models\Order::CONFIRMED]) && $order->payment_method == 'manual' || $order->payment_method == 'cod' || $order->payment_method == 'qris' && !$order->isPaid()): ?>
                                    <a href="<?php echo e(url('admin/orders/'. $order->id .'/cancel')); ?>" class="btn btn-block mt-2 btn-lg btn-warning btn-pill"> Cancel</a>

                                    <?php endif; ?>
                                    <?php if($order->payment_status == 'waiting' && $order->payment_method == 'qris'): ?>
                                            <form action="<?php echo e(route('admin.orders.confirmAdmin', $order->id)); ?>" method="POST">
                                                <?php echo method_field('PUT'); ?>
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill"> Confirm Payment</button>
                                            </form>
                                    <?php elseif($order->payment_status == 'waiting' && $order->payment_method == 'manual'): ?>
                                            <form action="<?php echo e(route('admin.orders.confirmAdmin', $order->id)); ?>" method="POST">
                                                <?php echo method_field('PUT'); ?>
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill"> Confirm Payment</button>
                                            </form>
                                    <?php elseif($order->payment_status == 'unpaid' && $order->payment_method == 'manual'): ?>
                                        <form action="<?php echo e(route('admin.orders.confirmAdmin', $order->id)); ?>" method="POST">
                                            <?php echo method_field('PUT'); ?>
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill"> Confirm Payment</button>
                                        </form>
                                    <?php elseif($order->payment_status == 'unpaid' && $order->payment_method == 'cod'): ?>
                                        <form action="<?php echo e(route('admin.orders.confirmAdmin', $order->id)); ?>" method="POST">
                                            <?php echo method_field('PUT'); ?>
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill"> Confirm Payment</button>
                                        </form>
                                    <?php elseif($order->payment_status == 'unpaid' && $order->payment_method == 'qris'): ?>
                                        <form action="<?php echo e(route('admin.orders.confirmAdmin', $order->id)); ?>" method="POST">
                                            <?php echo method_field('PUT'); ?>
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill"> Confirm Payment</button>
                                        </form>
                                    <?php elseif($order->payment_status == 'unpaid' && $order->payment_method == 'toko'): ?>
                                        <form action="<?php echo e(route('admin.orders.confirmAdmin', $order->id)); ?>" method="POST">
                                            <?php echo method_field('PUT'); ?>
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill"> Confirm Payment</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if($order->isDelivered()): ?>
                                        <a href="#" class="btn btn-block mt-2 btn-lg btn-success btn-pill" onclick="event.preventDefault();
                                        document.getElementById('complete-form-<?php echo e($order->id); ?>').submit();"> Mark as Completed</a>
                                        <form class="d-none" method="POST" action="<?php echo e(route('admin.orders.complete', $order)); ?>" id="complete-form-<?php echo e($order->id); ?>">
                                            <?php echo csrf_field(); ?>
                                        </form>
                                    <?php endif; ?>

                                    <?php if(!in_array($order->status, [\App\Models\Order::DELIVERED, \App\Models\Order::COMPLETED])): ?>
                                        <a href="#" class="btn btn-block mt-2 btn-lg btn-secondary btn-pill delete" order-id="<?php echo e($order->id); ?>"> Remove</a>
                                        <form action="<?php echo e(route('admin.orders.destroy',$order)); ?>" method="post" id="delete-form-<?php echo e($order->id); ?>" class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('delete'); ?>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="<?php echo e(url('admin/orders/restore/'. $order->id)); ?>" class="btn btn-block mt-2 btn-lg btn-outline-secondary btn-pill restore">Restore</a>
                                    <a href="#" class="btn btn-block mt-2 btn-lg btn-danger btn-pill delete" order-id="<?php echo e($order->id); ?>"> Remove Permanently</a>
                                    <form action="<?php echo e(route('admin.orders.destroy',$order)); ?>" method="post" id="delete-form-<?php echo e($order->id); ?>" class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('delete'); ?>
                                        </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php if($order->attachments != null): ?>
                                <div class="col-md-6 mb-5">
                                    <a class="btn btn-primary" href="<?php echo e(route('download-file', $order->id)); ?>">Download Attachments File</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
              </div>
              
            </div>
            
          </div>
          
        </div>
        
      </div>
      
    </section>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('style-alt'); ?>
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-alt'); ?>
    <script
        src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous"
    >
    </script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script>
    $("#data-table").DataTable();

    $(".delete").on("submit", function () {
        return confirm("Do you want to remove this?");
    });
    $("a.delete").on("click", function () {
        event.preventDefault();
        var orderId = $(this).attr('order-id');
        if (confirm("Do you want to remove this?")) {
            document.getElementById('delete-form-' + orderId ).submit();
        }
    });

    $(".restore").on("click", function () {
        return confirm("Do you want to restore this?");
    });

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>