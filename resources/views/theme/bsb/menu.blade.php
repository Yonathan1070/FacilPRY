@foreach ($menusComposer as $key => $item)
    @include("theme.bsb.menu-item", ["item" => $item])
@endforeach