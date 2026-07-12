const chartXAxisTypes = ["temporal", "ordinal", "category"];
const chartYAxisFormats = ["integer", "decimal", "percent", "currency"];
function localizedText(value = "") {
  return { en: value };
}
function first(values, fallback) {
  return values[0] ?? fallback;
}
function isoDate(value) {
  return value ? value.slice(0, 10) : null;
}
function optionItems(values) {
  return values.map((value) => ({ value, label: value }));
}
function isRecord(value) {
  return typeof value === "object" && value !== null && !Array.isArray(value);
}
function numericValues(value) {
  return value.split(",").map((item) => Number(item.trim())).filter(Number.isFinite);
}
function lines(value) {
  return value.split("\n").map((item) => item.trim()).filter((item) => item !== "");
}
function isChartXAxisType(value) {
  return typeof value === "string" && chartXAxisTypes.includes(value);
}
function isChartYAxisFormat(value) {
  return typeof value === "string" && chartYAxisFormats.includes(value);
}
function defaultPayload(type) {
  if (type === "kpi") {
    return { items: [{ label: "Risk index", value: "42", direction: "up", sparkline: [24, 36, 42] }] };
  }
  const isBar = type === "bar";
  return {
    labels: isBar ? ["Scenario A", "Scenario B", "Scenario C"] : ["Now", "Next", "Later"],
    series: [{ name: "Scenario", values: [20, 42, 64] }],
    axes: {
      x: { label: isBar ? "Category" : "Sequence", type: isBar ? "category" : "ordinal" },
      y: { label: "Value", unit: "%", format: "percent" }
    }
  };
}
function parseChartPayload(text) {
  const labels = text.labels.split(",").map((label) => label.trim()).filter((label) => label !== "");
  const series = lines(text.series).map((line, index) => {
    const [rawName, rawValues] = line.includes(":") ? line.split(":", 2) : [`Series ${index + 1}`, line];
    return { name: rawName.trim() || `Series ${index + 1}`, values: numericValues(rawValues ?? "") };
  }).filter((item) => item.values.length > 0);
  return {
    labels,
    series,
    axes: {
      x: { label: text.xLabel.trim(), type: text.xType },
      y: { label: text.yLabel.trim(), unit: text.yUnit.trim(), format: text.yFormat }
    }
  };
}
function parseKpiPayload(itemsText) {
  const items = itemsText.split("\n").map((line) => {
    const [label = "", value = "", direction = "up", sparkline = ""] = line.split("|").map((item) => item.trim());
    return { label, value, direction, sparkline: numericValues(sparkline) };
  }).filter((item) => item.label !== "" && item.value !== "");
  return { items };
}
function chartText(payload) {
  if (!isRecord(payload)) {
    return {
      labels: "Now, Next, Later",
      xLabel: "Sequence",
      xType: "ordinal",
      yLabel: "Value",
      yUnit: "%",
      yFormat: "percent",
      series: "Scenario: 20, 42, 64"
    };
  }
  const axes = isRecord(payload.axes) ? payload.axes : {};
  const xAxis = isRecord(axes.x) ? axes.x : {};
  const yAxis = isRecord(axes.y) ? axes.y : {};
  const rawSeries = Array.isArray(payload.series) ? payload.series : [];
  const series = rawSeries.every((item) => Number.isFinite(Number(item))) ? `Series: ${rawSeries.map(String).join(", ")}` : rawSeries.map((item, index) => {
    if (!isRecord(item)) {
      return "";
    }
    const name = typeof item.name === "string" ? item.name : `Series ${index + 1}`;
    const values = Array.isArray(item.values) ? item.values.map(String).join(", ") : "";
    return `${name}: ${values}`;
  }).filter((item) => item !== "").join("\n");
  return {
    labels: Array.isArray(payload.labels) ? payload.labels.map(String).join(", ") : "Now, Next, Later",
    xLabel: typeof xAxis.label === "string" ? xAxis.label : "Sequence",
    xType: isChartXAxisType(xAxis.type) ? xAxis.type : "ordinal",
    yLabel: typeof yAxis.label === "string" ? yAxis.label : "Value",
    yUnit: typeof yAxis.unit === "string" ? yAxis.unit : "%",
    yFormat: isChartYAxisFormat(yAxis.format) ? yAxis.format : "percent",
    series: series || "Scenario: 20, 42, 64"
  };
}
function kpiText(payload) {
  if (!isRecord(payload) || !Array.isArray(payload.items)) {
    return "Risk index|42|up|24,36,42";
  }
  return payload.items.filter(isRecord).map((item) => {
    const label = String(item.label ?? "");
    const value = String(item.value ?? "");
    const direction = String(item.direction ?? "up");
    const sparkline = Array.isArray(item.sparkline) ? item.sparkline.map(String).join(",") : "";
    return `${label}|${value}|${direction}|${sparkline}`;
  }).join("\n") || "Risk index|42|up|24,36,42";
}
export {
  isRecord as a,
  chartYAxisFormats as b,
  chartXAxisTypes as c,
  defaultPayload as d,
  chartText as e,
  first as f,
  parseKpiPayload as g,
  isoDate as i,
  kpiText as k,
  localizedText as l,
  optionItems as o,
  parseChartPayload as p
};
