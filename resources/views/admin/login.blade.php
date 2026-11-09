<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Connexion · Admin Trésorerie Plus</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @fonts
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center"><x-logo /></div>
        <form method="POST" action="{{ route('admin.login.store') }}" class="card space-y-5 p-8">
            @csrf
            <h1 class="text-xl font-extrabold">Espace administrateur</h1>
            <x-field name="email" label="E-mail" type="email" required autocomplete="username" autofocus />
            <div>
                <label for="password" class="field-label">Mot de passe</label>
                <input id="password" name="password" type="password" class="field-input" required autocomplete="current-password">
            </div>
            <label class="flex items-center gap-2 text-sm text-ink-soft">
                <input type="checkbox" name="remember" value="1" class="accent-brand-600"> Rester connecté
            </label>
            <button type="submit" class="btn btn-primary w-full">Se connecter</button>
        </form>
    </div>
</body>
</html>
