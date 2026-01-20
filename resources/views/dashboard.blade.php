@extends('layouts.tech')

@section('title', 'Dashboard')

@section('content')

<h2 style="margin-bottom:20px;">Dashboard</h2>

<div class="row">
    <div class="col-md-3">
        <div class="card-tech p-3 mb-3">
            <h3>{{ DB::table('offres_stage')->count() }}</h3>
            <p>Offres de stage</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-tech p-3 mb-3">
            <h3>{{ DB::table('candidatures')->count() }}</h3>
            <p>Candidatures</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-tech p-3 mb-3">
            <h3>{{ DB::table('stages')->count() }}</h3>
            <p>Stages</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-tech p-3 mb-3">
            <h3>{{ DB::table('entreprises')->count() }}</h3>
            <p>Entreprises</p>
        </div>
    </div>
</div>

<hr>

<h4>Dernières candidatures</h4>

<div class="table-responsive">
<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Offre</th>
            <th>Étudiant</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach(DB::table('candidatures')->orderByDesc('id')->limit(5)->get() as $c)
        <tr>
            <td>#{{ $c->id }}</td>
            <td>{{ $c->offre_stage_id }}</td>
            <td>{{ $c->etudiant_id }}</td>
            <td>
                <span class="badge {{ $c->statut }}">
                    {{ $c->statut }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

@endsection
