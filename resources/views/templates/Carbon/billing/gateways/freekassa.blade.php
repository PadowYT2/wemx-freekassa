@section('tablist')
    @parent
    @isset($settings['freekassa_gateway'])
        @if ($settings['freekassa_gateway'] == 'true')
            <li class="nav-item">
                <a class="nav-link navs-btn mb-sm-3 mb-md-0" id="tabs-icons-text-freekassa-tab" data-toggle="tab"
                    href="#tabs-icons-text-freekassa" role="tab" aria-controls="tabs-icons-text-freekassa"
                    aria-selected="false">FreeKassa</a>
            </li>
        @endif
    @endisset
@stop

@section('tablist-content')
    @parent
    @isset($settings['freekassa_gateway'])
        @if ($settings['freekassa_gateway'] == 'true')
            <div class="tab-pane fade show" id="tabs-icons-text-freekassa" role="tabpanel"
                aria-labelledby="tabs-icons-text-freekassa-tab">
                <form action="{{ route('freekassa.checkout') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label" for="balance">{!! Bill::lang()->get('amount_info') !!}</label>
                                <input class="form-control" id="gift-card-code-input" name="amount" type="number"
                                    value="5">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%"><strong>{!! Bill::lang()->get('confirm') !!}</strong></button>
                </form>
            </div>
        @endif
    @endisset
@stop
