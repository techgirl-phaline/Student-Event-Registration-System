
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config/db.php";

$upcomingEvents = [];

$sessionQuery = $conn->query(
    "SELECT
        session_id,
        session_title,
        description,
        session_date,
        session_time,
        venue,
        status
     FROM sessions
     WHERE session_date >= CURDATE()
       AND status IN ('Upcoming', 'Active')
     ORDER BY session_date ASC, session_time ASC"
);

if ($sessionQuery) {

    while ($session = $sessionQuery->fetch_assoc()) {

        $upcomingEvents[] = [

            "id" => $session["session_id"],

            "title" => $session["session_title"],

            "category" => "College Event",

            "date" => date(
                "d F Y",
                strtotime($session["session_date"])
            ),

            "time" => date(
                "h:i A",
                strtotime($session["session_time"])
            ),

            "venue" => $session["venue"],

            "status" => $session["status"],

            "description" => $session["description"]

        ];

    }

}

$pastEvents = [

    [
        "title" => "environmental",
        "image" => "images/events/environmental images.jpeg"
    ],

    [
        "title" => "Academic & Study Skills Seminar",
        "image" => "images/events/academic images.jpeg"
    ],

    [
        "title" => "Clinical Skills Competition",
        "image" => "images/events/clinical images.jpeg"
    ],

    [
        "title" => "Blood Donation Drive",
        "image" => "images/events/donation images.jpeg"
    ],

    [
        "title" => "Sports & Athletics Day",
        "image" => "images/events/sports images.jpeg"
    ],

    [
        "title" => "ICT & Innovation Day",
        "image" => "images/events/ict images.jpeg"
    ],

    [
        "title" => "Mental Health & Wellness Day",
        "image" => "images/health download (1).jpeg"
    ],

    [
        "title" => "Cultural & Talent Day",
        "image" => "images/cultural images.jpeg"
    ],

    [
        "title" => "Community Health Outreach",
        "image" => "images/events/outreach images.jpeg"
    ],

    [
        "title" => "Career & Professional Development Day",
        "image" => "images/events/career images.jpeg"
    ],

    [
        "title" => "Science & Research Exhibition",
        "image" => "images/events/science_exihbition images.jpeg"
    ],

    [
        "title" => "Student Awards & Closing Ceremony",
        "image" => "images/events/awards images.jpeg"
    ]

];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Events | M Student Events</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f8fc;
            color: #172033;
        }

        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e9ef;
            padding: 17px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            text-decoration: none;
            color: #123b68;
            font-size: 23px;
            font-weight: 800;
        }
        .navbar-logo {
            height: 58px;
            width: auto;
            object-fit: contain;
            display: block;
        }
        .brand-name {
    display: block;
    line-height: 1.15;
    } 
    .brand-area {
    display: flex;
    align-items: center;
}

.brand-link {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.brand-link .logo {
    white-space: nowrap;
}

        .logo span {
            color: #1c79c9;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links a {
            text-decoration: none;
            color: #4b5563;
            font-size: 14px;
            font-weight: 600;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #1c79c9;
        }

        .register-nav {
            background: #123b68;
            color: white !important;
            padding: 11px 18px;
            border-radius: 9px;
        }

        .register-nav:hover {
            background: #1c79c9;
        }

        .page-header {
            background: linear-gradient(
                135deg,
                #123b68,
                #1c79c9
            );

            color: white;
            text-align: center;
            padding: 75px 20px;
        }

        .page-header h1 {
            margin: 0 0 15px;
            font-size: 44px;
            font-weight: 800;
        }

        .page-header p {
            max-width: 720px;
            margin: auto;
            line-height: 1.8;
            color: #e8f1f9;
        }

        .search-container {
            width: 88%;
            max-width: 1100px;
            margin: -35px auto 0;
            position: relative;
            z-index: 10;
        }

        .search-box {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(18, 59, 104, 0.12);
        }

        .search-box input {
            width: 100%;
            border: 1px solid #dce3eb;
            border-radius: 10px;
            padding: 15px 18px;
            font-size: 15px;
            outline: none;
        }

        .search-box input:focus {
            border-color: #1c79c9;
            box-shadow: 0 0 0 3px rgba(28, 121, 201, 0.10);
        }

        .events-section {
            width: 88%;
            max-width: 1200px;
            margin: 75px auto;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-heading h2 {
            margin-bottom: 10px;
            color: #123b68;
            font-size: 32px;
        }

        .section-heading p {
            color: #6b7280;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .event-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e5eaf0;
            transition: 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(18, 59, 104, 0.13);
        }

        .event-image {
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(
                135deg,
                #123b68,
                #1c79c9
            );
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .event-content {
            padding: 23px;
        }

        .category {
            display: inline-block;
            background: #eaf4fc;
            color: #1c79c9;
            padding: 6px 11px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .status {
            display: inline-block;
            background: #edf8f1;
            color: #23844b;
            padding: 6px 11px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            margin-left: 5px;
        }

        .event-content h3 {
            color: #123b68;
            font-size: 19px;
            line-height: 1.4;
            margin: 0 0 10px;
        }

        .event-description {
            color: #687385;
            font-size: 14px;
            line-height: 1.7;
            min-height: 72px;
        }

        .event-details {
            border-top: 1px solid #edf0f4;
            margin-top: 18px;
            padding-top: 16px;
        }

        .detail {
            display: flex;
            gap: 9px;
            margin-bottom: 9px;
            font-size: 13px;
            color: #596273;
        }

        .detail-label {
            color: #1c79c9;
            font-weight: 700;
            min-width: 48px;
        }

        .event-button {
            display: block;
            width: 100%;
            background: #123b68;
            color: white;
            text-decoration: none;
            text-align: center;
            padding: 12px;
            border-radius: 9px;
            margin-top: 17px;
            font-size: 14px;
            font-weight: 700;
            transition: 0.3s;
        }

        .event-button:hover {
            background: #1c79c9;
        }

        .no-events {
            grid-column: 1 / -1;
            background: white;
            border: 1px solid #e5eaf0;
            border-radius: 18px;
            text-align: center;
            padding: 70px 20px;
        }

        .no-events-icon {
            font-size: 55px;
            margin-bottom: 15px;
        }

        .no-events h3 {
            color: #123b68;
            margin-bottom: 10px;
        }

        .no-events p {
            color: #6b7280;
            line-height: 1.7;
        }

        .past-events-section {
            width: 88%;
            max-width: 1200px;
            margin: 90px auto;
        }

        .gallery-heading {
            text-align: center;
            margin-bottom: 40px;
        }

        .gallery-heading h2 {
            color: #123b68;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .gallery-heading p {
            color: #6b7280;
            line-height: 1.7;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .gallery-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid #e5eaf0;
            transition: 0.3s ease;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(18, 59, 104, 0.12);
        }

        .gallery-image {
            width: 100%;
            height: 190px;
            overflow: hidden;
            background: #e9eef4;
        }

        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .gallery-card:hover .gallery-image img {
            transform: scale(1.06);
        }

        .gallery-content {
            padding: 17px;
        }

        .gallery-content h3 {
            color: #123b68;
            font-size: 15px;
            line-height: 1.5;
            margin: 0;
        }

        .past-label {
            display: inline-block;
            background: #f0f3f6;
            color: #687385;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 9px;
        }

        #noResults {
            display: none;
            text-align: center;
            padding: 60px 20px;
        }

        #noResults h3 {
            color: #123b68;
        }

        #noResults p {
            color: #6b7280;
        }

        footer {
            background: #102f52;
            color: white;
            padding: 50px 6% 25px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 40px;
        }

        footer h3 {
            margin-top: 0;
        }

        footer p {
            color: #c7d2de;
            line-height: 1.7;
        }

        footer a {
            display: block;
            color: #c7d2de;
            text-decoration: none;
            margin-bottom: 10px;
        }

        footer a:hover {
            color: white;
        }

        .copyright {
            border-top: 1px solid rgba(255,255,255,0.12);
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            color: #aebdca;
            font-size: 13px;
        }

        @media (max-width: 1000px) {

            .events-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
            }

        }

        @media (max-width: 650px) {

            .navbar {
                display: block;
                text-align: center;
            }

            .nav-links {
                margin-top: 15px;
                justify-content: center;
                flex-wrap: wrap;
                gap: 14px;
            }

            .page-header h1 {
                font-size: 34px;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .gallery-image {
                height: 150px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 450px) {

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .gallery-image {
                height: 200px;
            }

        }

    </style>

</head>

<body>

<nav class="navbar"> 

    <div class="brand-area">

        <a href="index.php" class="brand-link">
            <img src="images/logo Image .png" 
                 alt="MMTC Logo" 
                 class="navbar-logo">

            <span class="logo">
                MMTC <span>Events</span>
            </span>
        </a>

    </div>

    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="events.php" class="active">
            Events
        </a>

        <a href="about.php">
            About
        </a>

        <a href="contact.php">
            Contact
        </a>

        <a href="register.php" class="register-nav">
            Register Now
        </a>

    </div>

</nav>

<section class="page-header">

    <h1>
        College Events
    </h1>

    <p>
        Discover upcoming college activities and explore
        memorable events from previous years at Macmillan
        Medical Training College.
    </p>

</section>

<div class="search-container">

    <div class="search-box">

        <input
            type="text"
            id="eventSearch"
            placeholder="Search events by name, category or venue..."
        >

    </div>

</div>

<section class="events-section">

    <div class="section-heading">

        <h2>
            Upcoming Events
        </h2>

        <p>
            View events created by the college administration
            and register to participate.
        </p>

    </div>

    <div
        class="events-grid"
        id="eventsGrid"
    >

        <?php if (count($upcomingEvents) > 0): ?>

            <?php foreach ($upcomingEvents as $event): ?>

                <?php

                $eventImage = "images/events/food competion images.jpeg";

                $title = strtolower($event["title"]);

                if (strpos($title, "environmental") !== false) {

                    $eventImage = "images/events/environmental images.jpeg";

                } elseif (strpos($title, "academic") !== false) {

                    $eventImage = "images/events/academic images.jpeg";

                } elseif (strpos($title, "clinical") !== false) {

                    $eventImage = "images/events/clinical images.jpeg";

                } elseif (strpos($title, "blood") !== false) {

                    $eventImage = "images/events/donation images.jpeg";

                } elseif (strpos($title, "sports") !== false) {

                    $eventImage = "images/events/sports images.jpeg";

                } elseif (
                    strpos($title, "innovation") !== false ||
                    strpos($title, "ict") !== false
                ) {

                    $eventImage = "images/events/ict images.jpeg";

                } elseif (
                    strpos($title, "wellness") !== false ||
                    strpos($title, "health awareness") !== false ||
                    strpos($title, "mental health") !== false
                ) {

                    $eventImage = "images/health download (1).jpeg";

                } elseif (
                    strpos($title, "cultural") !== false ||
                    strpos($title, "talent") !== false
                ) {

                    $eventImage ="images/cultural images.jpeg";

                } elseif (
                    strpos($title, "community") !== false ||
                    strpos($title, "outreach") !== false
                ) {

                    $eventImage = "images/outreach.jpg";

                } elseif (strpos($title, "career") !== false) {

                    $eventImage = "images/events/career images.jpeg";

                } elseif (
                    strpos($title, "research") !== false ||
                    strpos($title, "science") !== false
                ) {

                    $eventImage = "images/events/science_exihbition images.jpeg";

                } elseif (
                    strpos($title, "awards") !== false ||
                    strpos($title, "closing") !== false
                ) {

                    $eventImage = "images/events/awards images.jpeg";

                }

                ?>

                <div
                    class="event-card"
                    data-search="<?php

                        echo htmlspecialchars(
                            strtolower(
                                $event["title"] . " " .
                                $event["category"] . " " .
                                $event["venue"] . " " .
                                $event["description"]
                            )
                        );

                    ?>"
                >

                    <div class="event-image">

                        <img
                            src="<?php echo htmlspecialchars($eventImage); ?>"
                            alt="<?php echo htmlspecialchars($event["title"]); ?>"
                        >

                    </div>

                    <div class="event-content">

                        <span class="category">

                            <?php

                            echo htmlspecialchars(
                                $event["category"]
                            );

                            ?>

                        </span>

                        <span class="status">

                            <?php

                            echo htmlspecialchars(
                                $event["status"]
                            );

                            ?>

                        </span>

                        <h3>

                            <?php

                            echo htmlspecialchars(
                                $event["title"]
                            );

                            ?>

                        </h3>

                        <p class="event-description">

                            <?php

                            echo htmlspecialchars(
                                $event["description"]
                            );

                            ?>

                        </p>

                        <div class="event-details">

                            <div class="detail">

                                <span class="detail-label">
                                    Date
                                </span>

                                <span>

                                    <?php

                                    echo htmlspecialchars(
                                        $event["date"]
                                    );

                                    ?>

                                </span>

                            </div>

                            <div class="detail">

                                <span class="detail-label">
                                    Time
                                </span>

                                <span>

                                    <?php

                                    echo htmlspecialchars(
                                        $event["time"]
                                    );

                                    ?>

                                </span>

                            </div>

                            <div class="detail">

                                <span class="detail-label">
                                    Venue
                                </span>

                                <span>

                                    <?php

                                    echo htmlspecialchars(
                                        $event["venue"]
                                    );

                                    ?>

                                </span>

                            </div>

                        </div>

                        <a
                            href="register.php?event=<?php

                                echo urlencode(
                                    $event["title"]
                                );

                            ?>"
                            class="event-button"
                        >

                            Register for Event →

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="no-events">

                <div class="no-events-icon">
                    📅
                </div>

                <h3>
                    No Upcoming Events
                </h3>

                <p>
                    There are currently no upcoming events.
                    Please check back later for new events
                    created by the college administration.
                </p>

            </div>

        <?php endif; ?>

    </div>

    <div id="noResults">

        <h3>
            No Events Found
        </h3>

        <p>
            Try searching for another event, category or venue.
        </p>

    </div>

</section>

<section class="past-events-section">

    <div class="gallery-heading">

        <h2>
            Past Events Gallery
        </h2>

        <p>
            A look back at some of the activities and events
            that have brought the MMTC student community together.
        </p>

    </div>

    <div
        class="gallery-grid"
        id="galleryGrid"
    >

        <?php foreach ($pastEvents as $pastEvent): ?>

            <div
                class="gallery-card"
                data-gallery-search="<?php

                    echo htmlspecialchars(
                        strtolower(
                            $pastEvent["title"]
                        )
                    );

                ?>"
            >

                <div class="gallery-image">

                    <img
                        src="<?php

                            echo htmlspecialchars(
                                $pastEvent["image"]
                            );

                        ?>"
                        alt="<?php

                            echo htmlspecialchars(
                                $pastEvent["title"]
                            );

                        ?>"
                    >

                </div>

                <div class="gallery-content">

                    <span class="past-label">
                        Past Event
                    </span>

                    <h3>

                        <?php

                        echo htmlspecialchars(
                            $pastEvent["title"]
                        );

                        ?>

                    </h3>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</section>

<footer>

    <div class="footer-grid">

        <div>

            <h3>
                MMTC Events
            </h3>

            <p>
                Student Event Registration Management System
                for Macmillan Medical Training College, Nairobi.
            </p>

        </div>

        <div>

            <h3>
                Quick Links
            </h3>

            <a href="index.php">
                Home
            </a>

            <a href="events.php">
                Events
            </a>

            <a href="register.php">
                Register
            </a>

        </div>

        <div>

            <h3>
                Contact
            </h3>

            <p>
                Nairobi, Kenya
            </p>

            <p>
                Student Events Office
            </p>

        </div>

    </div>

    <div class="copyright">

        © <?php echo date("Y"); ?>
        Macmillan Medical Training College.
        Student Event Registration System.

    </div>

</footer>

<script>

    const searchInput =
        document.getElementById("eventSearch");

    const eventCards =
        document.querySelectorAll(".event-card");

    const galleryCards =
        document.querySelectorAll(".gallery-card");

    const noResults =
        document.getElementById("noResults");

    searchInput.addEventListener(
        "input",
        function () {

            const searchText =
                this.value.toLowerCase().trim();

            let visibleItems = 0;

            eventCards.forEach(
                function (card) {

                    const eventData =
                        card.dataset.search;

                    if (
                        eventData.includes(
                            searchText
                        )
                    ) {

                        card.style.display =
                            "block";

                        visibleItems++;

                    } else {

                        card.style.display =
                            "none";

                    }

                }
            );

            galleryCards.forEach(
                function (card) {

                    const galleryData =
                        card.dataset.gallerySearch;

                    if (
                        galleryData.includes(
                            searchText
                        )
                    ) {

                        card.style.display =
                            "block";

                        visibleItems++;

                    } else {

                        card.style.display =
                            "none";

                    }

                }
            );

            if (
                visibleItems === 0 &&
                searchText !== ""
            ) {

                noResults.style.display =
                    "block";

            } else {

                noResults.style.display =
                    "none";

            }

        }
    );

</script>

</body>

</html>

<?php

$conn->close();

?>






