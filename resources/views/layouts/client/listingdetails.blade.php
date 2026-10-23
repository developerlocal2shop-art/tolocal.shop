<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('includes.client.head')
    <body>
        <div id="page">
            <style>
                .btn-custom {
                    color: #fff;
                    border-radius: 5px;
                    font-weight: bold;
                    background-color: {{ $data['mainDirectoryDetails']->company_color ?: '#2f4a7c' }};
                }

                @media (min-width: 992px) {
                    header.header.listing-detail-header.listing-detail-header-fixed {
                        position: fixed !important;
                        top: 0 !important;
                        right: 0;
                        left: 0;
                        z-index: 10000;
                    }

                    header.header.listing-detail-header > nav#menu.main-menu {
                        display: flex !important;
                        visibility: visible;
                    }

                    header.header.listing-detail-header .city_name_desktop {
                        display: block !important;
                        visibility: visible;
                    }

                    header.header.listing-detail-header > .listing-local-promo {
                        display: flex !important;
                        visibility: visible;
                    }
                }
            </style>
            <div class="listing-detail-page-content">
                <!-- Header -->
                <header class="header mm-slideout menu_fixed container-fluid listing-detail-header header_in" style="background-color: {{ $data['mainDirectoryDetails']->company_color ?: '#2f4a7c' }};">
                    <x-ClientmenuListingDetail :mainDirectoryDetails="$data['mainDirectoryDetails']" />
                </header>
                @yield('content')
            </div>
            <x-ClientCompanyShopFooter :mainDirectoryDetails="$data['mainDirectoryDetails']" />

            <script>
                (function () {
                    var header = document.querySelector('.listing-detail-header');
                    var pageContent = document.querySelector('.listing-detail-page-content');
                    var footer = document.querySelector('.vendor_footer');
                    var desktopViewport = window.matchMedia('(min-width: 992px)');

                    if (!header || !pageContent) {
                        return;
                    }

                    var updateFixedHeader = function () {
                        if (!desktopViewport.matches) {
                            header.classList.remove('listing-detail-header-fixed');
                            return;
                        }

                        header.classList.add('listing-detail-header-fixed');
                        header.classList.remove('sticky');
                        var headerHeight = header.getBoundingClientRect().height;
                        pageContent.style.paddingTop = headerHeight + 'px';

                        if (footer) {
                            header.classList.toggle(
                                'listing-detail-header-hidden-at-footer',
                                footer.getBoundingClientRect().top <= headerHeight
                            );
                        }
                    };

                    updateFixedHeader();
                    window.addEventListener('resize', updateFixedHeader);
                    window.addEventListener('scroll', updateFixedHeader);
                })();
            </script>
        </div>
    </body>
</html>
