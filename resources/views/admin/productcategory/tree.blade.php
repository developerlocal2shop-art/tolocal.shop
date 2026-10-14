@extends('layouts.adminmaster')
@section('title','Category| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp

<style>
    ul.wtree{
        list-style-type: none;
    }

    a {
        color: #768494 !important;
    }
    .treemenu .accordion-heading {  position: relative; }
    .treemenu .accordion-heading .edit {
        position: absolute;
        top: 8px;
        right: 30px;
    }
    .treemenu .area { border-left: 4px solid #f38787; }
    .treemenu .equipamento { border-left: 4px solid #65c465; }
    .treemenu .ponto { border-left: 4px solid #98b3fa; }
    .treemenu .collapse.in { overflow: visible; }


    .accordion{margin-bottom:20px;}
    .accordion-group{margin-bottom:2px;border:1px solid #e5e5e5;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}
    .accordion-heading{border-bottom:0;}
    .accordion-heading .accordion-toggle{display:block;padding:8px 15px;}
    .accordion-toggle{cursor:pointer;}
    .accordion-inner{padding:9px 9px;border-top:1px solid #e5e5e5;}
</style>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-6 col-sm-4 col-xs-12">
                <h4 class="page-title">Product Categories Tags Tree Structure</h4>
            </div>

        </div>
    </div>

    <div class="container-fluid">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="treemenu">
                                <?php $i=1; 
                                foreach($data['product_parent_category'] as $parentCategory){ 
                                    $get_child_category = Helper::getProductchildCategory($parentCategory->id); ?>
                                    <div class="accordion">
                                        <!-- Áreas -->
                                        <div class="accordion-group">
                                            <!-- Área -->
                                            <div class="accordion-heading area">
                                                <a class="accordion-toggle" data-toggle="collapse" href="#maincategories_{{ $parentCategory->id }}"><b>
                                                {{ $parentCategory->id.'- '.$parentCategory->name }}</b></a>
                                            </div><!-- /Área -->

                                            <div class="accordion-body collapse" id="maincategories_{{ $parentCategory->id }}">
                                                <div class="accordion-inner">
                                                <?php 
                                                $j='a'; 
                                                foreach($get_child_category as $childCategory){ 
                                                    
                                                    $get_listing_tags = Helper::getAllProductTagsListing($childCategory->id); ?>
                                                    <div class="accordion" id="subcategory1">
                                                        <!-- Equipamentos -->

                                                        <div class="accordion-group">
                                                            <div class="accordion-heading equipamento">
                                                                <a class="accordion-toggle" data-parent="#subcategory1-{{ $childCategory->id }}" data-toggle="collapse" href="#tags1-{{ $childCategory->id }}">
                                                                   {{ $childCategory->id.'. '.$childCategory->name }}
                                                                </a>
                                                            </div><!-- Pontos -->

                                                            <?php  ?><div class="accordion-body collapse" id="tags1-{{ $childCategory->id }}">
                                                            <?php if(count($get_listing_tags)>0){ ?>
                                                                <div class="accordion-inner">
                                                                    <div class="accordion" id="servico1">
                                                                        <div class="accordion-group">
                                                                            <div class="accordion-heading ponto">
                                                                                <a class="accordion-toggle" data-parent="#servico1-1-1" data-toggle="collapse" href=""><span>
                                                                                <?php foreach($get_listing_tags as $tagsname){ ?>
                                                                                (<b>{{ $tagsname->id??'' }}</b>) {{ $tagsname->tags??'' }}, 
                                                                                <?php } ?></span></a>
                                                                            </div><!-- Serviços -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            </div><?php  ?><!-- /Pontos -->
                                                        </div><!-- /Equipamentos -->
                                                    </div>
                                                <?php $j++; } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /accordion -->
                                <?php $i++; } ?>

                                </div>
                            </div>
                        </div>
                    </div>

                <?php /* foreach($data['parent_category'] as $parentCategory){
                    $get_child_category = Helper::getchildCategory($parentCategory->id);
                ?>
                    <p><b>{{ $parentCategory->name }}</b></p>
                    <?php foreach($get_child_category as $childCategory){
                        $get_listing_tags = Helper::getListingTags($childCategory->id);
                        //$tagsDatainLine = implode(",", $get_listing_tags);
                    ?>
                        <p>* {{ $childCategory->name }} | <?php foreach($get_listing_tags as $tagsname){ ?>{{ $tagsname }}, <?php } ?></p>

                    <?php } ?>
                <?php } */ ?>
                </div>
            </div>
        </div>


    </div>

</div>


<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

    var toggler = document.getElementsByClassName("caret");
    var i;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
    }
</script>

@endsection
