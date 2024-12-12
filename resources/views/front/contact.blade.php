<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{ asset('output.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    </head>
    <body>
        <div class="relative flex flex-col w-full max-w-[640px] min-h-screen gap-5 mx-auto bg-[#F5F5F0]">
            <div class="flex flex-col items-center justify-center px-4 gap-[30px] my-auto">
                <form class="flex flex-col w-full max-w-[330px] rounded-[30px] p-5 gap-6 bg-white">
                    @csrf
                    <img src="{{ asset ('assets/images/icons/whatsapp.svg') }}" class="w-[90px] h-[90px] mx-auto" alt="icon">
                    <h1 class="font-bold text-2xl leading-9 text-center">Klik Below to chat whatsapp</h1>
                    <div class="flex flex-col gap-3">
                        <a href="https://wa.me/+62895333186603" class="rounded-full p-[12px_20px] text-center w-full bg-[#C5F277] font-bold"> Whatsapp
                        </a>
                    </div>
                </div>
                <div id="bottom-nav" class="relative flex h-[100px] w-full shrink-0">
                    <nav class="fixed bottom-5 w-full max-w-[640px] px-4 z-30">
                        <div class="grid grid-flow-col auto-cols-auto items-center justify-between rounded-full bg-[#2A2A2A] p-2 px-[30px]">
                            <a href="{{ route ('front.index') }}" class="mx-auto w-full">
                                <img src="{{ asset ('assets/images/icons/3dcube-white.svg') }}" class="w-6 h-6" alt="icon">
                            </a>
                            <a href="{{ route ('front.check_booking') }}" class="mx-auto w-full">
                                    <img src="{{ asset ('assets/images/icons/bag-2-white.svg') }}" class="w-6 h-6" alt="icon">
                            </a>
                             <a href="{{ route('front.all_category') }}" class="mx-auto w-full">
                                <img src="{{asset('assets/images/icons/star-white.svg') }}" class="w-6 h-6" alt="icon">
                            </a>
                            <a href="{{ route('front.contact') }}" target="_blank" class="active flex shrink-0 -mx-[22px]">
                                <div class="flex items-center rounded-full gap-[10px] p-[12px_16px] bg-[#C5F277]">
                                    <img src="{{ asset ('assets/images/icons/whatsapp.svg') }}" class="w-6 h-6" alt="icon">
                                    <span class="font-bold text-sm leading-[21px]">Contact</span>
                                </div>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </body>
</html>