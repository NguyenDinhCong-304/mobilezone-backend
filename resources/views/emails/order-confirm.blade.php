<h3>Cảm ơn bạn đã đặt hàng tại NDC Shop!</h3>
<p>Mã đơn hàng: <strong>#{{ $order->id }}</strong></p>
<p>Tổng tiền: <strong>{{ number_format($order->total_price) }} đ</strong></p>
<p>Địa chỉ giao hàng: {{ $order->address }}</p>
<p>Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.</p>
