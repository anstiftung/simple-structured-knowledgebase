FROM node:18-alpine3.17 as build
WORKDIR /app
COPY . /app
RUN yarn install
RUN yarn run build --base=/ -m=staging

FROM nginx:1.25-alpine
# COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
