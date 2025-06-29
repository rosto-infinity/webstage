<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inventaire des Produits</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .date { color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Inventaire des Produits</h1>
        <p class="date">Généré le : {{ $date }}</p>

          <div class="total">
              Total des produits : {{ $products->count() }}<br>
              Valeur totale de l'inventaire : {{ number_format($totalValue, 0, ',', ' ') }} CFA
          </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix (CFA)</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                 <td>{{ str_pad($product->id, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 0, ',', ' ') }}</td>
                <td>{{ $product->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
