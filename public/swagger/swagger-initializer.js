window.onload = () => {
  window.ui = SwaggerUIBundle({
    url: "/openapi.yaml",  // <== Aqui define o caminho para seu arquivo YAML
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    layout: "StandaloneLayout"
  });
};
