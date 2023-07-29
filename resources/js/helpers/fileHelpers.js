export function basename(path) {
    const lastSlashIndex = path.lastIndexOf('/')
    if (lastSlashIndex === -1) {
        return path
    }

    return path.slice(lastSlashIndex + 1)
}
