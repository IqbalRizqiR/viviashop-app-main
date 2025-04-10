<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Slide</h3>
                <a href="<?php echo e(route('admin.slides.create')); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-plus"></i> Tambah </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>    
                                <td><?php echo e($slide->id); ?></td>
                                <td><?php echo e($slide->title); ?></td>
                                <td><img width="200" src="<?php echo e(asset('/storage/' . $slide->path)); ?>" /></td>
                                <td>
                                    <?php if($slide->prevSlide()): ?>
                                        <a href="<?php echo e(url('admin/slides/'. $slide->id .'/up')); ?>">up</a>
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                        | 
                                    <?php if($slide->nextSlide()): ?>
                                        <a href="<?php echo e(url('admin/slides/'. $slide->id .'/down')); ?>">down</a>
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                    position : <?php echo e($slide->position); ?>

                                </td>
                                <td><?php echo e($slide->status); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('admin.slides.edit', $slide)); ?>" class="btn btn-sm btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form onclick="return confirm('are you sure !')" action="<?php echo e(route('admin.slides.destroy', $slide)); ?>"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6">No records found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/slides/index.blade.php ENDPATH**/ ?>