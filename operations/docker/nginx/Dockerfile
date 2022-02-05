# https://registry.hub.docker.com/_/nginx
FROM nginx:1.20.2

COPY nginx.conf /etc/nginx/nginx.conf
COPY http.conf /etc/nginx/conf.d/default.conf

CMD /bin/bash -c "nginx -g 'daemon off;'"
