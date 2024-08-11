<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: url('/assets/img/mail-bg.svg');
            background-position: top;
            width: 100%;
            height: 100vh;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <div>
        <div style="display: flex;gap:20px;align-items:center;justify-content:center; flex-direction:column">
            <div style="display:flex;gap:20px;align-items:center;">
                <svg style="width: 50px;height:50px;color:#0097FF;" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
                <h1 style="font-weight: 500;margin-top:10px;">
                    KaryaMu
                </h1>
            </div>
            <h3 style="font-weight: 400;">Selamat!, karya kamu terpublish</h3>

            <img src="{{ asset('assets/img/thumb-mail.png') }}" style="width: 40%;border-radius:20px" alt="">

            <div style="display: flex;gap:20px;align-items:center;">
                <h3 style="font-weight: 400;">Karya</h3>
                <h3 style="font-weight: 500;">"Creative Studio"</h3>
            </div>
            <button>
                Lihat Karya
            </button>
        </div>

    </div>
</body>

</html>
