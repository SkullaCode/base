<?php


namespace App\Interfaces;

interface ISession extends \Countable
{
    /**
     * Get a session variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Set a session variable.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return ISession
     */
    public function set($key, $value);

    /**
     * Merge values recursively.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return ISession
     */
    public function merge($key, $value);

    /**
     * Delete a session variable.
     *
     * @param string $key
     *
     * @return ISession
     */
    public function delete($key);

    /**
     * Clear all session variables.
     *
     * @return ISession
     */
    public function clear();

    /**
     * Check if a session variable is set.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);

    /**
     * Get or regenerate current session ID.
     *
     * @param bool $new
     *
     * @return string
     */
    public static function id($new = false);

    /**
     * Destroy the session.
     */
    public static function destroy();
}