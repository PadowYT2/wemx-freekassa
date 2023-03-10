<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">FreeKassa Configuration</a></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-8">
                <label class="control-label">FreeKassa Payment Gateway</label>
                <div>
                    <select class="form-control" name="freekassa_gateway" required>
                        <option value="@if (isset($settings['freekassa_gateway'])) {{ $settings['freekassa_gateway'] }} @endif"
                            selected="">
                            @isset($settings['freekassa_gateway'])
                                @if ($settings['freekassa_gateway'] == 'true')
                                    FreeKassa Enabled
                                @else
                                    FreeKassa Disabled
                                @endif
                            @endisset
                        </option>
                        <option value="true">Enable</option>
                        <option value="false">Disable</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label class="control-label">Shop ID</label>
                <div>
                    <input type="text" class="form-control" name="freekassa_shop_id"
                        value="@if (isset($settings['freekassa_shop_id'])) {{ $settings['freekassa_shop_id'] }} @endif">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Secret Key</label>
                <div>
                    <input type="password" class="form-control"
                        name="freekassa_secret_key"value="@if (isset($settings['freekassa_secret_key'])) {{ $settings['freekassa_secret_key'] }} @endif">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Two Secret Key</label>
                <div>
                    <input type="password" class="form-control"
                        name="freekassa_secret_key_two"value="@if (isset($settings['freekassa_secret_key_two'])) {{ $settings['freekassa_secret_key_two'] }} @endif">
                </div>
            </div>
        </div>
    </div>
</div>
