<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoom Meetings List</title>
</head>
<body>
    <div class="container">
        <h2>Zoom Meetings List</h2>

        @if(count($meetings['meetings']) > 0)
            <table border="1">
                <thead>
                    <tr>
                        <th>Meeting ID</th>
                        <th>Topic</th>
                        <th>Start Time</th>
                        <th>Host</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meetings['meetings'] as $meeting)
                        <tr>
                            <td>{{ $meeting['id'] }}</td>
                            <td>{{ $meeting['topic'] }}</td>
                            <td>{{ $meeting['start_time'] }}</td>
                            <td>{{ $meeting['host_id'] }}</td>
                            <td>
                                @if ($meeting['host_id'] == auth()->user()->zoom_user_id)
                                    <a href="{{ route('start.meeting', ['meetingId' => $meeting['id']]) }}">Start</a>
                                @else
                                    <!-- Display a message or take other actions for non-host users -->
                                    Not Host
                                @endif
                            
                                <form method="POST" action="{{ route('meetings.delete', ['meetingId' => $meeting['id']]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No meetings available.</p>
        @endif
    </div>
</body>
</html>
