import { resolve } from 'node:path'

// @ts-expect-error There's no type definition for `@tailwindcss/vite` at the moment
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin'
import { defineConfig, loadEnv } from 'vite'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, './workbench', ['APP', 'VITE'])
  const isDev = ['local', 'testing'].includes(env.APP_ENV)
  const rootDir = 'resources/client'

  return {
    envDir: './workbench',

    resolve: {
      alias: {
        '~': resolve(__dirname, rootDir),
      },
    },

    build: {
      sourcemap: isDev,
    },

    plugins: [
      /**
       * @see https://laravel.com/docs/vite
       */
      laravel({
        input: [
          `${rootDir}/app.ts`,
        ],
        hotFile: 'vendor/orchestra/testbench-core/laravel/public/hot',
        refresh: true,
      }),

      tailwindcss(),
    ],
  }
})
