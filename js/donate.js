const onceTab = document.getElementById("once-tab");
const monthlyTab = document.getElementById("monthly-tab");

const amountButtons = document.querySelectorAll(".amount-btn");

const oneTimeAmounts = [
  {
    amount: "$25",
    text: "Helps provide basic supplies for a family",
  },
  {
    amount: "$75",
    text: "Supports transportation and essential needs",
  },
  {
    amount: "$150",
    text: "Helps provide safety planning and support",
  },
  {
    amount: "$500",
    text: "Contributes toward housing and stability",
  },
];

const monthlyAmounts = [
  {
    amount: "$10/mo",
    text: "Provides ongoing support for a child",
  },
  {
    amount: "$25/mo",
    text: "Helps sustain family resources monthly",
  },
  {
    amount: "$50/mo",
    text: "Supports long-term healing programs",
  },
  {
    amount: "$100/mo",
    text: "Helps fund transitional housing support",
  },
];

function updateAmounts(amounts) {
  amountButtons.forEach((button, index) => {
    if (index < 4) {
      button.querySelector("strong").textContent = amounts[index].amount;

      button.querySelector("span").textContent = amounts[index].text;
    }
  });
}

onceTab.addEventListener("click", () => {
  onceTab.classList.add("active");
  monthlyTab.classList.remove("active");

  updateAmounts(oneTimeAmounts);
});

monthlyTab.addEventListener("click", () => {
  monthlyTab.classList.add("active");
  onceTab.classList.remove("active");

  updateAmounts(monthlyAmounts);
});

amountButtons.forEach((button) => {
  button.addEventListener("click", () => {
    amountButtons.forEach((btn) => btn.classList.remove("selected"));

    button.classList.add("selected");
  });
});
