<audio
    id="newOrderSound"
    preload="none">
    <source
        src="{{ asset('sounds/new-order.mp3') }}"
        type="audio/mpeg">
</audio>

<script>
(function () {

    'use strict';

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    */

    // 10 Minutes = 10 * 60 * 1000 = 600000 ms
    const POLL_INTERVAL = 20000;

    const STORAGE_KEY = 'admin_last_order_notification_id';

    const audio = document.getElementById('newOrderSound');

    let lastNotificationId = parseInt(
        sessionStorage.getItem(STORAGE_KEY) || '0',
        10
    );

    let initialized = false;
    let audioUnlocked = false;
    let polling = false;

    /*
    |--------------------------------------------------------------------------
    | Unlock Audio
    |--------------------------------------------------------------------------
    */

    function unlockAudio() {
        if (!audio || audioUnlocked) {
            return;
        }

        audio.volume = 1;
        const playPromise = audio.play();

        if (playPromise !== undefined) {
            playPromise
                .then(function () {
                    audio.pause();
                    audio.currentTime = 0;
                    audioUnlocked = true;
                })
                .catch(function () {
                    // Browser autoplay blocked waiting for interaction
                });
        }
    }

    document.addEventListener('click', unlockAudio, { once: false, passive: true });
    document.addEventListener('keydown', unlockAudio, { once: false, passive: true });

    function playNewOrderSound() {
        if (!audio) return;

        audio.pause();
        audio.currentTime = 0;
        audio.volume = 1;

        const playPromise = audio.play();

        if (playPromise !== undefined) {
            playPromise.catch(function () {
                // Autoplay blocked, popup will still work
            });
        }
    }

  

    function showNewOrderNotification(notification) {
        const orderId = notification.order_id;
        const title = notification.title || 'New Order Received';
        const message = notification.message || 'A new order has been received.';

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                html: `<div style="font-size:16px;">${message}</div>`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'View Order',
                cancelButtonText: 'Close',
                allowOutsideClick: false,
                allowEscapeKey: true,
            }).then(function (result) {
                if (result.isConfirmed && orderId) {
                    window.location.href = '/admin/orders/' + orderId;
                }
            });
        } else {
            const shouldView = confirm(message + '\n\nView Order?');
            if (shouldView && orderId) {
                window.location.href = '/admin/orders/' + orderId;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    async function initializeNotifications() {
        try {
            const response = await fetch(
                '{{ route('admin.order.notifications') }}?after_id=0',
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    cache: 'no-store',
                }
            );

            if (!response.ok) return;

            const result = await response.json();

            if (!result.status) return;

            if (!sessionStorage.getItem(STORAGE_KEY)) {
                lastNotificationId = parseInt(result.latest_id || 0, 10);
                sessionStorage.setItem(STORAGE_KEY, lastNotificationId);
            }

            initialized = true;

        } catch (error) {
            // Silent fail
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Poll New Notifications
    |--------------------------------------------------------------------------
    */

    async function checkNewOrderNotifications() {
        // Agar initialize nahi hua hai toh return kardo
        if (!initialized) return;

        // Agar user doosre tab mein hai, toh API call mat karo (Save server resources)
        if (document.hidden) return;

        // Agar ek request pehle se chal rahi hai toh doosri mat bhejo
        if (polling) return;

        polling = true;

        try {
            const url = '{{ route('admin.order.notifications') }}?after_id=' + lastNotificationId;

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                cache: 'no-store',
            });

            if (!response.ok) return;

            const result = await response.json();

            if (!result.status || !Array.isArray(result.data) || result.data.length === 0) {
                return;
            }

            // Process New Notifications
            result.data.forEach(function (notification) {
                playNewOrderSound();
                showNewOrderNotification(notification);

                lastNotificationId = parseInt(notification.id, 10);
                sessionStorage.setItem(STORAGE_KEY, lastNotificationId);
            });

        } catch (error) {
            // Silent ignore network errors
        } finally {
            polling = false;
        }
    }

    initializeNotifications();

    // Har 10 Minute mein check karega
    setInterval(checkNewOrderNotifications, POLL_INTERVAL);

    // Jab user tab par wapas aayega tab automatically check karega
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden && initialized) {
            checkNewOrderNotifications();
        }
    });

})();
</script>