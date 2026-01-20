@extends('layouts.app') 

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="text-center p-5 rounded-4" style="background: #0f172a; border: 2px solid #ef4444; max-width: 500px;">
        <div class="display-1 mb-3">🚫</div>
        <h2 class="text-white fw-bold mb-3">Compte Suspendu</h2>
        <p class="text-secondary mb-4">
            Votre accès au portail a été restreint par l'administration. 
            Veuillez contacter le support pour plus d'informations.
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger px-4">Retour à l'accueil</button>
        </form>
    </div>
</div>
@endsection