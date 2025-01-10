<div class="container text-center">
    {{-- <div class="collapse navbar-collapse" id="navbar-menu">
        <ul class="nav navbar-nav navbar-right" data-in="#" data-out="#">
            <li>
                <select wire:model="searchYear" class="top3">
                    <option value="">SEMUA TAHUN</option>
                    @forelse (range(now()->year-5,now()->year) as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                    @empty
                    <option></option>
                    @endforelse
                  </select>
            </li>
            <li>
                <select wire:model="searchFakultas" class="top3">
                    <option value="">SEMUA FAKULTAS</option>
                    @foreach ($getSelectFakultas as $item)
                    @if ($item->id != 1000)

                    <option value="{{ $item->id }}">{{ $item->nama_fakultas }}</option>
                    @endif

                    @endforeach
                  </select>
            </li>
        </ul>
    </div> --}}
    <div class="row" style="margin-top: 33px">
        <div class="col-lg-6">
            <select wire:model="searchYear" class="form-control">
                <option value="">SEMUA TAHUN</option>
                @forelse (range(now()->year-5,now()->year) as $item)
                <option value="{{ $item }}">{{ $item }}</option>
                @empty
                <option></option>
                @endforelse
            </select>
        </div>
        <div class="col-lg-6">
            <select wire:model="searchFakultas" class="form-control">
                <option value="">SEMUA FAKULTAS</option>
                @foreach ($getSelectFakultas as $item)
                @if ($item->id != 1000)

                <option value="{{ $item->id }}">{{ $item->nama_fakultas }}</option>
                @endif

                @endforeach
            </select>
        </div>
    </div>
    
</div>
