<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background: #f6f6f6; padding: 20px;">

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table width="600" style="background: #ffffff; padding: 25px; border-radius: 8px;">

                    <!-- HEADER -->
                    <tr>
                        <td style="text-align: center; padding-bottom: 20px;">
                            <h2 style="margin: 0; color: #333;">Order Confirmation</h2>
                            <p style="margin: 5px 0; color: #777;">Order #{{ $order['order_number'] }}</p>
                        </td>
                    </tr>

                    <!-- CUSTOMER GREETING -->
                    <tr>
                        <td style="font-size: 16px; color: #333; padding-bottom: 15px;">
                            Hello <strong>{{ $order['customer_name'] }}</strong>,
                            <br><br>
                            Thank you for shopping with us! Your order has been successfully received and is now being processed.
                        </td>
                    </tr>

                    <!-- ORDER DETAILS -->
                    <tr>
                        <td>
                            <table width="100%" cellpadding="10" style="background: #fafafa; border-radius: 6px; margin-bottom: 20px;">
                                <tr>
                                    <td style="font-size: 15px; color: #333;">
                                        <strong>Order Number:</strong> {{ $order['order_number'] }} <br>
                                        <strong>Customer Name:</strong> {{ $order['customer_name'] }} <br>
                                        <strong>Address:</strong> {{ $order['customer_address'] }} <br>
                                        <strong>Total Amount:</strong> ₹{{ number_format($order['total_amount'], 2) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ITEMS SECTION -->
                    <tr>
                        <td>
                            <h3 style="margin-bottom: 10px; color: #333;">Order Items</h3>

                            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #333; color: #fff;">
                                        <th align="left">Product</th>
                                        <th align="center">Qty</th>
                                        <th align="right">Price</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($order['items'] as $item)
                                    <tr style="background: #fafafa; border-bottom: 1px solid #eee;">
                                        <td>{{ $item['product_name'] }}</td>
                                        <td align="center">{{ $item['quantity'] }}</td>
                                        <td align="right">₹{{ number_format($item['price'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding-top: 30px; font-size: 15px; color: #555;">
                            We will notify you once your order is packed and shipped.
                            <br><br>
                            If you have any questions, feel free to reply to this email.
                            <br><br>
                            <strong>Warm regards,</strong><br>
                            <strong>SHREECOLLECTION Team</strong>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>