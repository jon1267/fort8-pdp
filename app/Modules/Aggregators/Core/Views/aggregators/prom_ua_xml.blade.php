<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<shop>
    <catalog>
        @foreach($categories as $category)
            <category id="{{ $category->id }}">{{ $category->name }}</category>
        @endforeach
    </catalog>
    <items>
        @foreach($products as $product)
            <item id="{{ $product->id }}" selling_type="r">
                <name>{{ $product->name }}</name>
                <categoryId>{{ $product->id }}</categoryId>
                <description>{{ $product->description }}</description>
                <priceuah>139</priceuah>
                <image>{{ url('/') . $product->img2 }}</image>
            </item>
        @endforeach
    </items>
</shop>
