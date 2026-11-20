FROM node:22-alpine AS build
ARG MODE
WORKDIR /app
COPY package.json yarn.lock ./
RUN yarn install --frozen-lockfile
COPY . /app
RUN if [ "$MODE" = "production" ]; then \
    yarn run build --base=/ -m=production; \
    else \
    yarn run build --base=/ -m=staging; \
    fi

FROM nginx:alpine
# COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
