@include('layouts.header', [
    'categories' => $categories,
    'cartCount' => $cartCount,
    'notificationsCount' => $notificationsCount,
    'favoritesCount' => $favoritesCount,
])

<style>
    /* === removing default button style ===*/
    .buttonpma {
        margin: 0;
        height: auto;
        background: transparent;
        padding: 0;
        border: none;
        animation: r1 3s ease-in-out infinite;
        /*linear*/
        border: 7px #056bfa21 solid;
        border-radius: 14px;
    }

    /* button styling */
    .buttonpma {
        --border-right: 6px;
        --text-stroke-color: rgba(85, 87, 255, 0.78);
        --animation-color: #056bfa;
        --fs-size: 2em;
        letter-spacing: 3px;
        text-decoration: none;
        font-size: var(--fs-size);
        font-family: "Arial";
        position: relative;
        text-transform: uppercase;
        color: transparent;
        -webkit-text-stroke: 1px var(--text-stroke-color);
    }

    /* this is the text, when you hover on button */
    .hover-text {
        position: absolute;
        box-sizing: border-box;
        content: attr(data-text);
        color: var(--animation-color);
        width: 0%;
        inset: 0;
        border-right: var(--border-right) solid var(--animation-color);
        overflow: hidden;
        transition: 1.5s;
        -webkit-text-stroke: 1px var(--animation-color);
        animation: r2 2s ease-in-out infinite;
    }

    /* hover */
    .buttonpma:hover .hover-text {
        width: 100%;
        filter: drop-shadow(0 0 70px var(--animation-color))
    }

    @keyframes r1 {
        50% {
            transform: rotate(-1deg) rotateZ(-10deg);
        }
    }

    @keyframes r2 {
        50% {
            transform: rotateX(-65deg);
        }
    }
</style>
<div class="container mt-5">
    @if (session('error'))
        <div class="alert alert-danger" style="border-radius: 0;" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if (!$errors->isEmpty())
        <div class="alert alert-danger" style="border-radius: 0;" role="alert">
            {{ $errors->first() }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" style="border-radius: 0;" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="color: black;">Order History</div>

                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Order Number</th>
                                <th scope="col">Status</th>
                                <th scope="col">Details</th>
                                <th scope="col">Total</th>
                                <th scope="col">Estimated Date to be Received</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orderHistoriesData->isEmpty())
                                <td colspan="6" class="text-center">No orders yet!</td>
                            @endif
                            @foreach ($orderHistoriesData as $order)
                                <tr>
                                    <td>{{ $order->orderNumber }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>
                                        <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#orderDetailsModal{{ $order->id }}">View Details</button>

                                        <!-- Order Details Modal -->
                                        <div class="modal fade" id="orderDetailsModal{{ $order->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="orderDetailsModalLabel{{ $order->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="orderDetailsModalLabel{{ $order->id }}">Order Details
                                                            for #{{ $order->orderNumber }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3>Bought from shop: {{ $order->shopName }} </h3>
                                                        <div class="row">
                                                            @foreach ($order->orderedProducts as $product)
                                                                <div class="col-md-4 mb-3">
                                                                    <div class="card">
                                                                        <img src="{{ $product->product->images->first()->imagePath }}"
                                                                            class="card-img-top"
                                                                            alt="{{ $product->product->name }}">
                                                                        <div class="card-body">
                                                                            <h5 class="card-title">
                                                                                {{ $product->product->name }}</h5>
                                                                            <h5 class="card-title">Php
                                                                                {{ number_format($product->product->price, 2) }}
                                                                            </h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Php {{ number_format($order->total, 2) }}</td>
                                    <td>{{ $order->estimateDate }}</td>
                                    <td>
                                        @if ($order->status == 'ON THE WAY')
                                            <form id="receiveOrderForm-{{ $order->id }}"
                                                action="/receiveOrder/{{ $order->id }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <button type="button" data-text="Awesome" class="buttonpma"
                                                    onclick="confirmReceiveOrder({{ $order->id }})">
                                                    <span class="actual-text">&nbsp;RECEIVED?&nbsp;</span>
                                                    <span class="hover-text"
                                                        aria-hidden="true">&nbsp;received?&nbsp;</span>
                                                </button>
                                            </form>
                                        @elseif ($order->status == 'PENDING')
                                            <a href="/pingSeller/{{ $order->orderNumber }}">
                                                <button data-text="Awesome" class="buttonpma">
                                                    <span class="actual-text">&nbsp;Ping Seller&nbsp;</span>
                                                    <span class="hover-text" aria-hidden="true">&nbsp;Ping&nbsp;</span>
                                                </button>
                                            </a>
                                        @elseif ($order->status == 'RECEIVED')
                                            <a href="#">
                                                <button data-text="Awesome" class="buttonpma">
                                                    <span class="actual-text">&nbsp;Received&nbsp;</span>
                                                    <span class="hover-text" aria-hidden="true">&nbsp;Enjoy
                                                        :)&nbsp;</span>
                                                </button>
                                            </a>
                                        @endif


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <h2>Total amount spent: Php {{ number_Format($totalSpent, 2) }}</h2>
                    <!-- Pagination if needed -->
                    <div class="d-flex justify-content-center">
                        {{ $orderHistoriesData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function confirmReceiveOrder(orderId) {
        if (confirm('Are you sure you have received this order?')) {
            document.getElementById('receiveOrderForm-' + orderId).submit();
        }
    }
</script>
@include('layouts.footer')
