
<h2>#New Order </h2></p>
<h2> id:{{$order->id}}</h2></p>
<p><strong>client:</strong> {{ $order->user->name }}</p>
<p><strong>total amount:</strong> {{ $order->total }} $</p>

<h3>products:</h3>
@foreach($order->orderItems as $item)
<p>
    {{ $item->product->name }} - 
    quantity: {{ $item->quantity }} - 
    price: {{ $item->price }} $
</p>
@endforeach

<p>Thank You</p>