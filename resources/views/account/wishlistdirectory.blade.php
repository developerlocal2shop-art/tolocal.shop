@extends('layouts.client.vendor')
@section('title','User Address')
@section('content')

<div class="container-fluid">
    <div class="row">
        <x-client.account_menu />
        <div class="col-lg-9">

            <div class="sub_header_in sticky_header filter_sub_blue_header">
                <div class="container">
                    <h1>Directory Wishlist</h1>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="border-top-0">image</th>
                            <th class="border-top-0">Directory Name</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; $count = count($wishlists); if($count>0){	?>
                        @foreach($wishlists as $row)
                        <tr>
                            <?php $imagename = Helper::getDirectoryimage($row->directory_id); 
                                    // $urldirectory = Helper::getSEO_URL($row->directory_id);
                                    $urldirectory = Helper::getDirectorySeoSlug($row->directory_id);
                                    ?>
                            <td>
                                <a href="<?php echo url('listings/details', ['id' => $urldirectory->slug]); ?>">
                                    <?php if(!empty($imagename)){ ?>
                                    <img src="{{ env('IMAGE_PATH').'images/directory/'.$imagename->name_thumbnail.'.webp' }}" height="50px" width="50px">
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" height="50px" width="50px">
                                    <?php } ?>
                                </a>
                            </td>
                            <td><a href="<?php echo url('listings/details', ['id' => $urldirectory->slug]); ?>" style="color: #000;"><?php echo $row->directory_name;?></a></td>
                            <td><a class="btn btn-xs show_confirm" href="<?php echo url("/account/directory-wislist-delete", ['id' => $row->id]); ?>" style="color: #da0000;"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td>
                        </tr>
                        @endforeach
                        <?php }else{ ?>
                        <tr>
                            <td align="center" colspan="10">No Data Found</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center pagination-wishlist cust-page">
                @if(isset($query))
                {{ $wishlists->appends($query)->links() }}
                @else
                {!! $wishlists->links() !!}
                @endif
            </div>
            <!-- /row -->
        </div>
    </div>
    <!-- /row -->
</div>
<!-- /container -->
<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

</script>
@endsection
