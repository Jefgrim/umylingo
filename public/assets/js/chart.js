const ctx = document.getElementById("bar-chart");
const ctx2 = document.getElementById("bar-chart2");

new Chart(ctx, {
    type: "line",
    data: {
        labels: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ],
        datasets: [
            {
                label: "Users per Month 2024",
                data: [16, 21, 8, 19, 25, 7, 8, 4, 6, 2, 0, 0],
                borderWidth: 2,
                borderColor: "#0c5894",
            },
        ],
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    },
});

new Chart(ctx2, {
    type: "bar",
    data: {
        labels: ["Philippines", "South Korea", "Japan"],
        datasets: [
            {
                label: "Number of Users per country",
                data: [60, 24, 32],
                borderWidth: 2,
                backgroundColor: ["#0c5894", "#0c5894", "#0c5894"],
            },
        ],
    },
});
