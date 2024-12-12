<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{ asset('output.css') }}"  rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    </head>
    <body>
        <div class="relative flex flex-col w-full max-w-[640px] min-h-screen gap-5 mx-auto bg-[#F5F5F0]">
            <div id="top-bar" class="flex justify-between items-center px-4 mt-[60px]">
                <a href="{{ route('front.index') }}">
                    <img src="{{asset('assets/images/logos/logo.svg') }}" class="flex shrink-0" alt="logo">
                </a>
                <a href="#">
                    <img src="{{asset('assets/images/icons/notification.svg') }}" class="w-10 h-10" alt="icon">
                </a>
            </div>
            <form action="{{ route('front.search_category') }}" class="flex justify-between items-center mx-4">
                <div class="relative flex items-center w-full rounded-l-full px-[14px] gap-[10px] bg-white transition-all duration-300 focus-within:ring-2 focus-within:ring-[#FFC700]">
                    <img src="{{asset('assets/images/icons/search-normal.svg') }}" class="w-6 h-6" alt="icon">
                    <input type="text" name="keyword" class="w-full py-[14px] appearance-none bg-white outline-none font-semibold placeholder:font-normal placeholder:text-[#878785]" placeholder="Search product...">
                </div>
                <button type="submit" class="h-full rounded-r-full py-[14px] px-5 bg-[#C5F277]">
                    <span class="font-semibold">Explore</span>
                </button>
            </form>
            <section id="category" class="flex flex-col gap-4 px-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold leading-[20px]">Our Categories</h2>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    @forelse ( $categories as $itemCategory )
                    <a href="{{ route('front.category', $itemCategory->slug) }}">

                        <div class="flex items-center justify-between w-full rounded-2xl overflow-hidden bg-white transition-all duration-300 hover:ring-2 hover:ring-[#FFC700]">
                            <div class="flex flex-col gap-[2px] px-[14px]">
                                <h3 class="font-bold text-sm leading-[21px]"> {{ $itemCategory->name }}</h3>
                                <p class="text-xs leading-[18px] text-[#878785]">{{ $itemCategory->shoes->count() }} Shoes</p>
                            </div>
                            <div class="flex shrink-0 w-20 h-[90px] overflow-hidden">
                                <img src="{{ Storage::url($itemCategory->icon) }}" class="w-full h-full object-cover object-left" alt="thumbnail">
                            </div>
                        </div>
                    </a>
                    @empty
                     <p>No Category Found</p>
                    @endforelse



                </div>
            </section>

            <div id="bottom-nav" class="relative flex h-[100px] w-full shrink-0">
                <nav class="fixed bottom-5 w-full max-w-[640px] px-4 z-30">
                    <div class="grid grid-flow-col auto-cols-auto items-center justify-between rounded-full bg-[#2A2A2A] p-2 px-[30px]">
                        <a href="{{ route ('front.index') }}" class="mx-auto w-full">
                                <img src="{{asset('assets/images/icons/3dcube-white.svg') }}" class="w-6 h-6" alt="icon">
                        </a>
                        <a href="{{ route('front.check_booking') }}" class="mx-auto w-full">
                            <img src="{{asset('assets/images/icons/bag-2-white.svg') }}" class="w-6 h-6" alt="icon">
                        </a>
                        <a href="#" class="mx-auto w-full">
                            <div class="flex items-center rounded-full gap-[10px] p-[12px_16px] bg-[#C5F277]">
                                  <img src="{{asset('assets/images/icons/search-normal.svg') }}" class="w-6 h-6" alt="icon">
                                <span class="font-bold text-sm leading-[21px]">Categories</span>
                            </div>
                        </a>
                        <a href="{{ route ('front.contact') }}" class="mx-auto w-full">
                            <img src="{{asset('assets/images/icons/whatsapp.svg') }}" class="w-6 h-6" alt="icon">
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="js/index.js"></script>
    </body>
</html>