<div>
    @php
    {{
        $thumbnailpath = $dataItem['thumb_url']; //Needs to be or external web images to be resized
        $imageId = $dataItem['id'].'.jpg';
        $imageResized = (new \App\Services\Api\V1\ResizeImage\BaseResizeImage)->retrieveImage(
            $thumbnailpath,
            [
                'id' => $dataItem['id'],
                'imageId' => $imageId
            ]
        );
        $imageResized = "data:image/jpeg;base64," .base64_encode($imageResized);
    }}
    @endphp
    <img src={{ $imageResized }}></img>
</div>
