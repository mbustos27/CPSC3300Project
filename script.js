document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed.");

    // Function to open popup
    function openPopup(content) {
        console.log("Opening popup with content:", content);
        document.getElementById('popup-content').innerHTML = content;
        document.querySelector('.popup-overlay').style.display = 'block';
        document.getElementById('popup').style.display = 'block';
    }

    // Function to close popup
    window.closePopup = function() {
        console.log("Closing popup.");
        document.querySelector('.popup-overlay').style.display = 'none';
        document.getElementById('popup').style.display = 'none';
    }

    // Load relation results in popup
    document.querySelectorAll('.relation-link').forEach(function(element) {
        console.log("Setting up click listener for relation link:", element);
        element.addEventListener('click', function(e) {
            e.preventDefault();
            var relation = this.getAttribute('data-relation');
            console.log("Relation link clicked. Fetching data for relation:", relation);
            fetch('nba.php?relation=' + relation)
                .then(response => {
                    console.log("Received response for relation:", response);
                    return response.text();
                })
                .then(data => {
                    console.log("Received data for relation:", data);
                    openPopup(data);
                })
                .catch(error => {
                    console.error("Error fetching relation data:", error);
                });
        });
    });

    // Load query results in popup
    document.querySelectorAll('.query-link').forEach(function(element) {
        console.log("Setting up click listener for query link:", element);
        element.addEventListener('click', function(e) {
            e.preventDefault();
            var query = this.getAttribute('data-query');
            console.log("Query link clicked. Fetching data for query:", query);
            fetch('nba.php?query=' + query)
                .then(response => {
                    console.log("Received response for query:", response);
                    return response.text();
                })
                .then(data => {
                    console.log("Received data for query:", data);
                    openPopup(data);
                })
                .catch(error => {
                    console.error("Error fetching query data:", error);
                });
        });
    });

    // Ad-hoc query form submission
    document.getElementById('adhocForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        console.log("Ad-hoc form submitted. Form data:", formData);
        fetch('nba.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log("Received response for ad-hoc query:", response);
            return response.text();
        })
        .then(data => {
            console.log("Received data for ad-hoc query:", data);
            openPopup(data);
        })
        .catch(error => {
            console.error("Error fetching ad-hoc query data:", error);
        });
    });
});
