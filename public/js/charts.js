var charts = [];
var fontColor = "#fff";

changeTheme();
updateChartData();

function makeChart(title, data, labels, ctx, type="pie") {
  return new Chart(ctx, {
    type: type,
    data: {
      labels: labels,
      datasets: [{
        data: data,
        hoverOffset: 20,
        backgroundColor: [
          "#C794AD",
          "#94ADC7",
          "#ADC794",
        ],
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      color: fontColor,
      animation: {
        duration: 2500,
        delay: 400,
      },
      layout: { padding: 20, },
      plugins: {
        title: {
          display: true,
          text: title,
          color: fontColor,
          font: {
            size: 22,
          }
        }
      }
    },
  });
}

async function updateChartData() {
  const graphs = ['gwad', 'tgp', 'tp'];

  // Do all the requests in parallel
  const requests = graphs.map(g => fetch(`/rates/${g}`));
  const responses = await Promise.all(requests);

  const errors = responses.filter(response => !response.ok);

  if (errors.length > 0) {
    throw errors.map((response) => Error(response.statusText));
  }

  const json = responses.map(response => response.json());
  const datas = await Promise.all(json);


  // For each blob of data, create a graph and swap the loading icon with the
  // graph.
  let chartIdx = 0;
  datas.forEach(d => {
    const canvas = newElement("canvas");
    const ctx = canvas.getContext('2d');

    const container = document.getElementById(`chart-${chartIdx}`)
    container.innerHTML = "";

    if (d.title === "Games Won and Drawn") {
      chartIdx += 1;
      const canvas2 = newElement("canvas");
      const cont2 = document.getElementById(`chart-${chartIdx}`)
      cont2.innerHTML = "";

      charts.push(makeChart(d.title, d.data[0], d.labels[0], ctx));
      charts.push(makeChart(d.title, d.data[1], d.labels[1], canvas2.getContext('2d')));
      cont2.appendChild(canvas2);
    } else {
      charts.push(makeChart(d.title, d.data, d.labels, ctx));
    }

    container.appendChild(canvas);

    chartIdx += 1;
  });

  const updateTime = document.getElementById("last-update");
  updateTime.innerText = `Última actualización: ${timeFromUnix(unixTime()*1000)}`;
}

function changeTheme() {
  fetch("/dark-mode")
    .then(response => response.json())
    .then(json => {
      if(json.darkMode == null) return;

      const body = document.getElementById("main-body");
      const title = document.getElementById("main-title");
      const updateTime = document.getElementById("last-update");
      let background;

      if(json.darkMode) {
        fontColor = "#fff";
        background = "#293042";
      } else {
        fontColor = "#000";
        background = "#eef2ff";
      }

      title.style.color = fontColor;
      updateTime.style.color = fontColor;
      body.style.backgroundColor = background;
    });
}
