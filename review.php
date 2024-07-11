<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Feedback Review</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        body {
            background: url('bg.jpg') no-repeat center center fixed; 
            background-size: cover;
            color: black;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            position: relative;
            max-width: 1200px;
            height: 600px;
            margin: 20px auto;
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: url('bg2.jpg') no-repeat center center fixed; 
            background-size: cover;   
             color: black ;
        }

        .box-container {
            display: flex;
            overflow-x: auto;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .box {
            min-width: 150px;
            height: 150px;
            margin: 0 10px;
            background-color: #C8CFBF;
            color: #1D2029;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }

        .conn{
            min-width: 300px;
            height: 50px;
            margin: 0 10px;
            background-color: #C8CFBF;
            color: #1D2029;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }
        .conn:hover {
            transform: scale(1.1);
            background-color: #737874 ;
        }


        .box:hover {
            transform: scale(1.1);
            background-color: #737874 ;
        }

        .box h3 {
            margin: 0;
            font-size: 1.2em;
        }

        .review-container {
            display: none;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1000px;
            margin-top: 20px;
        }

        .review-container.active {
            display: flex;
        }

        .review-header h2,
        .review-header p {
            margin: 0;
            color: white;
        }

        .review-body p {
            margin: 10px 0;
        }

        .timestamp {
            font-size: 0.9em;
            color: #ccc;
        }

        .close-button {
            align-self: flex-end;
            background: none;
            border: none;
            font-size: 1.5em;
            cursor: pointer;
            color: #ccc;
            transition: color 0.3s;
        }

        .close-button:hover {
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="conn">
        <h1>Client Feedback Review</h1>
    </div>

        <div class="box-container">
            <?php
            // Function to generate star icons based on rating
            function generateStars($rating) {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        $stars .= '<i class="fas fa-star"></i>'; // Solid star icon
                    } else {
                        $stars .= '<i class="far fa-star"></i>'; // Empty star icon
                    }
                }
                return $stars;
            }

            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "password";
            $dbname = "feedback_db";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch feedback from database
            $sql = "SELECT * FROM feedback ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='box' onclick='showReview(this)'>";
                    echo "<div class='box-content' data-name='{$row['name']}' data-email='{$row['email']}' data-comments='{$row['comments']}' data-overall-satisfaction='{$row['overall_satisfaction']}' data-project-quality='{$row['project_quality']}' data-timeliness='{$row['timeliness']}' data-professionalism='{$row['professionalism']}' data-value-for-money='{$row['value_for_money']}' data-like-most='{$row['like_most']}' data-dislike-most='{$row['dislike_most']}' data-improve='{$row['improve']}' data-additional-comments='{$row['additional_comments']}' data-created-at='{$row['created_at']}'>";
                    echo "<h3>{$row['name']}</h3>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No feedback available.</p>";
            }

            // Close connection
            $conn->close();
            ?>
        </div>

        <div class="review-container" id="reviewContainer">
            <button class="close-button" onclick="closeReview()">&times;</button>
            <div class="review-header">
                <h2 id="reviewName"></h2>
                <p id="reviewEmail"></p>
            </div>
            <div class="review-body">
                <p><strong>Comments:</strong> <span id="reviewComments"></span></p>
                <p><strong>Overall Satisfaction:</strong> <span id="reviewOverallSatisfaction"></span></p>
                <p><strong>Project Quality:</strong> <span id="reviewProjectQuality"></span></p>
                <p><strong>Timeliness:</strong> <span id="reviewTimeliness"></span></p>
                <p><strong>Professionalism of Staff:</strong> <span id="reviewProfessionalism"></span></p>
                <p><strong>Value for Money:</strong> <span id="reviewValueForMoney"></span></p>
                <p><strong>What did you like most:</strong> <span id="reviewLikeMost"></span></p>
                <p><strong>What did you dislike most:</strong> <span id="reviewDislikeMost"></span></p>
                <p><strong>How can we improve:</strong> <span id="reviewImprove"></span></p>
                <p><strong>Additional Comments:</strong> <span id="reviewAdditionalComments"></span></p>
                <p class="timestamp"><strong>Submitted at:</strong> <span id="reviewCreatedAt"></span></p>
            </div>
        </div>
    </div>

    <script>
        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<i class="fas fa-star"></i>'; // Solid star icon
                } else {
                    stars += '<i class="far fa-star"></i>'; // Empty star icon
                }
            }
            return stars;
        }

        function showReview(element) {
            const boxContent = element.querySelector('.box-content');
            document.getElementById('reviewName').textContent = boxContent.getAttribute('data-name');
            document.getElementById('reviewEmail').textContent = boxContent.getAttribute('data-email');
            document.getElementById('reviewComments').textContent = boxContent.getAttribute('data-comments');
            document.getElementById('reviewOverallSatisfaction').innerHTML = generateStars(boxContent.getAttribute('data-overall-satisfaction'));
            document.getElementById('reviewProjectQuality').textContent = boxContent.getAttribute('data-project-quality');
            document.getElementById('reviewTimeliness').textContent = boxContent.getAttribute('data-timeliness');
            document.getElementById('reviewProfessionalism').textContent = boxContent.getAttribute('data-professionalism');
            document.getElementById('reviewValueForMoney').textContent = boxContent.getAttribute('data-value-for-money');
            document.getElementById('reviewLikeMost').textContent = boxContent.getAttribute('data-like-most');
            document.getElementById('reviewDislikeMost').textContent = boxContent.getAttribute('data-dislike-most');
            document.getElementById('reviewImprove').textContent = boxContent.getAttribute('data-improve');
            document.getElementById('reviewAdditionalComments').textContent = boxContent.getAttribute('data-additional-comments');
            document.getElementById('reviewCreatedAt').textContent = boxContent.getAttribute('data-created-at');

            document.getElementById('reviewContainer').classList.add('active');
        }

        function closeReview() {
            document.getElementById('reviewContainer').classList.remove('active');
        }
    </script>
</body>

</html>
