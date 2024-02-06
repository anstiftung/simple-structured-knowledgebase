FROM node:18-alpine3.17 as build
ARG MODE
WORKDIR /app
COPY . /app
RUN yarn install
RUN if [ "$MODE" = "production" ]; then \
    yarn run build --base=/ -m=production; \
    else \
    yarn run build --base=/ -m=staging; \
    fi

FROM nginx:1.25-alpine
# COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
