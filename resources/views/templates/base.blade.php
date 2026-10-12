<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Invitation Template</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        .template-container {
            width: 100%;
            min-height: 800px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
        }

        .placeholder-text {
            position: absolute;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
            font-family: 'Great Vibes', cursive;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
        }

        .title-placeholder {
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 3rem;
            font-weight: bold;
            max-width: 80%;
        }

        .subtitle-placeholder {
            top: 35%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.8rem;
            max-width: 70%;
        }

        .date-placeholder {
            bottom: 35%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5rem;
            max-width: 60%;
        }

        .location-placeholder {
            bottom: 25%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.2rem;
            max-width: 50%;
        }

        .rsvp-placeholder {
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1rem;
            max-width: 40%;
            background-color: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 25px;
        }
    </style>
</head>
<body>
    <div class="template-container">
        @foreach($placeholders as $index => $placeholder)
            @php
                $position = match($index) {
                    0 => 'title-placeholder',
                    1 => 'subtitle-placeholder', 
                    2 => 'date-placeholder',
                    3 => 'location-placeholder',
                    4 => 'rsvp-placeholder',
                    default => 'placeholder-text'
                };
            @endphp
            <div class="placeholder-text {{ $position }}">
                {{ $placeholder }}
            </div>
        @endforeach
    </div>
</body>
</html>


