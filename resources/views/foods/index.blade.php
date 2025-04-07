@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Food Nutrition Database</h1>

        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Çıkış Yap</button>
            </form>
        @endauth

        <form method="GET" action="{{ route('food.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Yiyecek ara (Türkçe)" value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Ara</button>
            </div>
        </form>

        <div class="row">
            @foreach($foods as $food)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($food->photo)
                            <img src="{{ asset($food->photo) }}" class="card-img-top" alt="{{ $food->description }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted">No image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $food->turkish_description ?? $food->description }}</h5>
                            <p class="card-text">
                                @foreach($food->nutrients->take(3) as $nutrient)
                                    {{ $nutrient->nutrient_name }}: {{ $nutrient->amount }}{{ $nutrient->unit_name }}<br>
                                @endforeach
                            </p>

                            <a href="{{ route('food.show', $food) }}" class="btn btn-primary">Detayları Gör</a>

                            @auth
                                <a href="{{ route('food.edit', ['food' => $food, 'page' => $foods->currentPage()]) }}" class="btn btn-warning">Düzenle</a>
                                <form action="{{ route('food.destroy', $food) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bu yiyeceği silmek istediğinize emin misiniz?')">Sil</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    @if ($foods->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Önceki</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $foods->previousPageUrl() }}">Önceki</a></li>
                    @endif

                    @foreach ($foods->getUrlRange(1, $foods->lastPage()) as $page => $url)
                        <li class="page-item {{ $foods->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($foods->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $foods->nextPageUrl() }}">Sonraki</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Sonraki</span></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
@endsection
