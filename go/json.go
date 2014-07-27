package main

import (
	"encoding/json"
	"fmt"
)

type Request struct {
	Remote string `json:"remote"`
}

func main() {
	r := Request{
		Remote: "r",
	}
	j1, err1 := json.MarshalIndent(r, "", "  ")
	if err1 != nil {
		panic(err1)
	}
	fmt.Printf("%s\n", j1)
}
