<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<shop>
    <catalog>
        @foreach($categories as $category)
        <category id="{{ $category['id'] }}">{{ $category['name'] }}</category>
        @endforeach
    </catalog>
    <items>
        @foreach($products as $product)
        <item id="{{ $product->id.'-'.$product->volume  }}" selling_type="r">
            <vendor>{{ $product->vendor }}</vendor>

            @if($product->category_id == 1 && $product->volume == 50)
            <name>{{ $product->name .' 50 мл '.$categories[0]['short'] }}</name>
            <categoryId>1</categoryId>
            @elseif($product->category_id == 1 && $product->volume == 100)
            <name>{{ $product->name .' 100 мл '.$categories[1]['short'] }}</name>
            <categoryId>2</categoryId>
            @elseif($product->category_id == 2 && $product->volume == 50)
            <name>{{ $product->name .' 50 мл '.$categories[2]['short'] }}</name>
            <categoryId>3</categoryId>
            @elseif($product->category_id == 2 && $product->volume == 100)
            <name>{{ $product->name .' 100 мл '.$categories[3]['short'] }}</name>
            <categoryId>3</categoryId>
            @endif
            <description><![CDATA[{{ $product->description }}]]></description>
            <image>{{ url('/') . $product->img2 }}</image>
            <priceuah>{{ $product->price_ua }}</priceuah>
            <available>склад</available>
            <param name="Volume" unit="ml">{{ $product->volume }}</param>
            @if($product->category_id == 1 && $product->volume == 50)
            <keywords>{{ $product->name. ', '. $product->brand . $product->notes .', '. $categories[0]['name']}}</keywords>
            @elseif($product->category_id == 1 && $product->volume == 100)
            <keywords>{{ $product->name. ', '. $product->brand . $product->notes .', '. $categories[1]['name']}}</keywords>
            @elseif($product->category_id == 2 && $product->volume == 50)
            <keywords>{{ $product->name. ', '. $product->brand . $product->notes .', '. $categories[2]['name']}}</keywords>
            @elseif($product->category_id == 2 && $product->volume == 100)
            <keywords>{{ $product->name. ', '. $product->brand . $product->notes .', '. $categories[3]['name']}}</keywords>
            @endif
        </item>
        @endforeach
    </items>
</shop>
