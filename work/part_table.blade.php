@if(!empty($product->productVariants))
    @foreach($product->productVariants as $variant)
        <tr class="text-center">
            <td>{{ $variant->name }}</td>
            <td>{{ $variant->volume }}</td>
            <td>{{ $variant->art }}</td>
            <td>{{ $variant->price_ua }}</td>
            <td>{{ $variant->price_ru }}</td>
            <td>
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="customCheckbox1" {{ $variant->active_ua ? 'checked' : ''  }} disabled>
                    <label for="customCheckbox1" class="custom-control-label"></label>
                </div>
                {{--<input style="height: 2rem;" type="checkbox" {{ $variant->active_ua ? 'checked' : ''  }}>--}}
            </td>
            <td>
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="customCheckbox2" {{ $variant->active_ru ? 'checked' : ''  }} disabled >
                    <label for="customCheckbox2" class="custom-control-label"></label>
                </div>
                {{--<input type="checkbox" {{ $variant->active_ru ? 'checked' : ''  }}> --}}
            </td>
            <td>
                <!--  тут (в форме) писать код! копипаст  неотсюда !!!  -->
                <a href="#" id="delete_variants_table_button" class="btn btn-danger btn-sm" title="удалить вариант" >&nbsp;X&nbsp;</a>
            </td>
        </tr>
    @endforeach
@endif
