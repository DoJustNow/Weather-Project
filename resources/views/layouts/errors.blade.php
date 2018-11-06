@if ($errors->any())
    <div id="info-alert" class="wow flipInX alert alert-danger show fade col-8 mt-1 ml-auto mr-auto">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="alert-heading">Неудача :(</h4>
        @foreach ($errors->all() as $error)
            <hr>
            {{ $error }}
        @endforeach
    </div>
@endif