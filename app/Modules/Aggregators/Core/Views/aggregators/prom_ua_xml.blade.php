<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<shop>
    <catalog>
        @foreach($categories as $category)
            <category id="{{ $category->id }}">{{ $category->name }}</category>
        @endforeach
    </catalog>
    <items>
        @foreach($products as $product)
            <item id="{{ $product->id.'-'.$product->volume  }}" selling_type="r">
                <vendor>{{ $product->vendor }}</vendor>
                <name>{{ $product->name }}</name>
                <categoryId>{{ $product->category_id }}</categoryId>
                <description><![CDATA[{{ $product->description }}]]></description>
                <image>{{ url('/') . $product->img2 }}</image>
                <priceuah>{{ $product->price_ua }}</priceuah>
                <available>склад</available>
                <param name="Volume" unit="ml">{{ $product->volume }}</param>
                <keywords>{{ $product->name. ', '. $product->brand . $product->notes}}</keywords>

            </item>
        @endforeach
    </items>
</shop>
