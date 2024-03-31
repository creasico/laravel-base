// @ts-ignore
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin'
import { resolve } from 'path'
import { defineConfig, loadEnv } from 'vite'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, './workbench', ['APP', 'VITE'])
  const rootDir = 'resources/client'

  return {
    envDir: './workbench',

    resolve: {
      alias: {
        '~': resolve(__dirname, rootDir),
      },
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

      tailwindcss()
    ],
  }
})
