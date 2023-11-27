<!-- chat.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Chat App</title>
    <!-- Include Pusher and JavaScript -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    {{-- <div id="chat">
        <div id="messages"></div>
        <input type="text" id="messageInput" placeholder="Type a message...">
        <button onclick="sendMessage()">Send</button>
    </div> --}}

    <script>

        $(document).ready(function() {

            Pusher.logToConsole = true;
            var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
            });

            var channel = pusher.subscribe('stockalert-channel');
            channel.bind('stockalert-event-send-meesages', function(data) {
                console.log(data);

            });
        });



    </script>
</body>
</html>
