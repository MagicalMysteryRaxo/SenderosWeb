function getOrCreateID(key) {
  let id = localStorage.getItem(key);

  if (!id) {
    id = crypto.randomUUID();
    localStorage.setItem(key, id);
  }

  return id;
}

function getSessionID() {
  let sessionID = sessionStorage.getItem("sessionID");

  if (!sessionID) {
    sessionID = crypto.randomUUID();
    sessionStorage.setItem("sessionID", sessionID);
  }

  return sessionID;
}

function getUTM(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

function getDeviceType() {
  const width = window.innerWidth;

  if (width <= 600) return "mobile";
  if (width <= 1024) return "tablet";
  return "desktop";
}

function trackEvent(eventType, extraData = {}) {
  const eventData = {
    visitorID: getOrCreateID("visitorID"),
    sessionID: getSessionID(),
    eventType: eventType,
    pageURL: window.location.href,
    pageTitle: document.title,
    referrer: document.referrer,
    utmSource: getUTM("utm_source"),
    utmMedium: getUTM("utm_medium"),
    utmCampaign: getUTM("utm_campaign"),
    deviceType: getDeviceType(),
    screenWidth: window.innerWidth,
    language: navigator.language,
    ...extraData,
  };

  fetch("/api/track-event.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(eventData),
  }).catch((error) => {
    console.error("Analytics error:", error);
  });
}

trackEvent("page_view");

document.querySelectorAll("[data-track='donate-click']").forEach((button) => {
  button.addEventListener("click", () => {
    trackEvent("donate_click", {
      buttonText: button.innerText.trim(),
      buttonLocation: button.dataset.location || "unknown",
    });
  });
});
