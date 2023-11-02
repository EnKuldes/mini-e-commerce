<div>
    @php
    $i = rand(1,1000);
    @endphp
    <input type="checkbox" id="switch-{{ $i }}" {{ ($value == '1' ? 'checked' : '') }} class="toggle-switch" data-switch="bool" data-model="{{ $model }}" data-field="{{ $field }}" data-id="{{ $id }}" />
    <label for="switch-{{ $i }}" data-on-label="On" data-off-label="Off"></label>
    <script>
        $('#switch-{{ $i }}').change(function(){
            var data={model:$(this).data("model"),field:$(this).data("field"),value:1==$(this).is(":checked")?"1":"0",id:$(this).data("id")};
            formSend('{{ url('Request/Admin/Toggle-switch') }}', data, 'post');
        })
    </script>

</div>