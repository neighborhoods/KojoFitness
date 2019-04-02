FROM fluent/fluentd
RUN gem install fluent-plugin-grep
RUN ["gem", "install", "fluent-plugin-elasticsearch", "--no-rdoc", "--no-ri"]
RUN ["gem", "install", "fluent-plugin-prometheus", "--no-rdoc", "--no-ri"]
RUN ["gem", "install", "fluent-plugin-concat", "--no-rdoc", "--no-ri"]
RUN ["gem", "install", "fluent-plugin-rewrite-tag-filter", "--no-rdoc", "--no-ri"]
