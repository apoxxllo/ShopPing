@include('layouts.header', ['categories' => $categories, 'cartCount' => $cartCount, 'notificationsCount' => $notificationsCount])

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
                <div class="card-header" style="color: black;">Notifications</div>
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="unread-tab" data-toggle="tab" href="#unread" role="tab"
                                aria-controls="unread" aria-selected="true">Unread</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="read-tab" data-toggle="tab" href="#read" role="tab"
                                aria-controls="read" aria-selected="false">Read</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" id="notificationTabsContent">
                        <!-- Unread Notifications -->
                        <div class="tab-pane fade show active" id="unread" role="tabpanel"
                            aria-labelledby="unread-tab">
                            @if ($unreadNotifications->isEmpty())
                                <div style="color: black;" class="text-center mt-3">No unread notifications!</div>
                            @else
                                @foreach ($unreadNotifications as $notification)
                                    <div class="card mt-3">
                                        <div class="card-body" style="background:#f8c36c">
                                            {{-- <h5 class="card-title">Order #{{ $notification->order->orderNumber }}</h5> --}}
                                            <p style="color: black; font-size:1.5em; font-weight: bolder;" class="card-text" class="card-text">{{ $notification->description }}</p>
                                            <p style="color: #6195b1; font-size:1; font-weight: bolder;" class="card-text" class="card-text">Date: {{ $notification->created_at }}</p>
                                            {{-- <p class="card-text">Total: Php
                                                {{ number_format($notification->order->total, 2) }}</p> --}}
                                            {{-- <p class="card-text">Estimated Date:
                                                {{ $notification->order->estimateDate }}</p> --}}
                                            {{-- <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#orderDetailsModal{{ $notification->order->id }}">View
                                                Details</button> --}}
                                        </div>
                                    </div>

                                    {{-- <!-- Order Details Modal -->
                                    <div class="modal fade" id="orderDetailsModal{{ $notification->order->id }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="orderDetailsModalLabel{{ $notification->order->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="orderDetailsModalLabel{{ $notification->order->id }}">Order
                                                        Details for #{{ $notification->order->orderNumber }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        @foreach ($notification->order->orderedProducts as $product)
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
                                    </div> --}}
                                @endforeach
                            @endif

                            <!-- Pagination if needed -->
                            <div class="d-flex justify-content-center">
                                {{ $unreadNotifications->links() }}
                            </div>
                        </div>

                        <!-- Read Notifications -->
                        <div class="tab-pane fade" id="read" role="tabpanel" aria-labelledby="read-tab">
                            @if ($readNotifications->isEmpty())
                                <div style="color: black;" class="text-center mt-3">No read notifications!</div>
                            @else
                                @foreach ($readNotifications as $notification)
                                    <div class="card mt-3">
                                        <div class="card-body" style="background:#f8c36c">
                                            {{-- <h5 class="card-title">Order #{{ $notification->order->orderNumber }}</h5> --}}
                                            <p style="color: black; font-size:1.5em; font-weight: bolder;" class="card-text">{{ $notification->description }}</p>
                                            <p style="color: #6195b1; font-size:1em; font-weight: bolder;" class="card-text">Date: {{ $notification->created_at }}</p>
                                        </div>
                                    </div>

                                @endforeach
                            @endif

                            <!-- Pagination if needed -->
                            {{-- <div class="d-flex justify-content-center">
                                {{ $readNotifications->links() }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
